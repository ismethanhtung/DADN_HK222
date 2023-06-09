import mqtt from 'mqtt'
import User from './models/User.js'
import Record from './models/Record.js'
import Sensor from './models/Sensor.js'
import Log from './models/Log.js'
import socketMap from './socket.js'

const MQTTClients = []

class MQTTAdafruitIO {
    constructor(user_id, username, key, io) {
        this.user_id = user_id
        this.username = username
        this.key = key
        this.brokerUrl = `mqtt:io.adafruit.com//${username}:1883}`
        this.io = io
    }

    connect() {
        this.client = mqtt.connect(this.brokerUrl, {
            clean: true,
            connectTimeout: 4000,
            username: this.username,
            password: this.key,
            reconnectPeriod: 1000,
        })

        this.client.on('connect', () => {
            console.log(`${this.username} connected to Adafruit!`)
            MQTTClients.push(this)
        })

        this.client.on('message', async (topic, payload) => {
            payload = payload.toString()
            var feed_id = topic.replace(`${this.username}/feeds/`, '')
            const user = await User.findOne({ username: this.username })

            const sensor = await Sensor.findOneAndUpdate({ type: feed_id, user_id: user.id }, { value: payload })

            if (feed_id == 'yolo-light' || feed_id == 'yolo-temp' || feed_id == 'yolo-ph') {
                var newRecord = new Record({
                    data: payload,
                    sensor_id: sensor.id
                })
                newRecord.save()

                const records = await Record.find({ sensor_id: sensor.id })

                if (socketMap[user.id] != null) {
                    this.io.to(socketMap[user.id]).emit("record_recv", { type: sensor.type, records: records })
                }
            } else {
                if (feed_id == 'yolo-auto') {
                    var newLog = new Log({ user_id: user.id, activity: payload == '1' ? 'Chuyển sang chế độ tự động' : 'Chuyển sang chế độ thủ công' })
                    newLog.save()
                } else if (feed_id == 'yolo-pump') {
                    var newLog = new Log({ user_id: user.id, activity: payload == '1' ? 'Bật thủ công máy bơm' : 'Tắt thủ công máy bơm' })
                    newLog.save()
                }
            }

            if (socketMap[user.id] != null) {

                this.io.to(socketMap[user.id]).emit("data_recv", { type: sensor.type, value: payload })
            }

            console.log(`${feed_id}: ${payload}`)
        })

        this.client.on('disconnect', () => {
            console.log("Disconnected to Adafruit!")
        })
    }

    subscribe(feed_id) {
        this.client.subscribe(this.username + "/feeds/" + feed_id, () => {
            console.log("Subscribed to " + feed_id)
        })
    }

    publish(feed_id, data) {
        this.client.publish(this.username + "/feeds/" + feed_id, data, () => {
            console.log("Published to " + feed_id + " : " + data);
        })
    }
}

export default MQTTAdafruitIO
export { MQTTClients }