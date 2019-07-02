<template>
    <li class="dropdown notification-list">
        <a class="nav-link dropdown-toggle arrow-none" @click="clearAlert" data-toggle="dropdown" href="#" id="topbar-notifydrop" role="button" aria-haspopup="true"
           aria-expanded="false">
            <i class="dripicons-bell noti-icon" :class="{'text-danger': this.has_new}"></i>
            <span class="badge badge-danger align-middle" v-if="this.has_new">{{ this.count }}</span>
        </a>

        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-lg" aria-labelledby="topbar-notifydrop">
            <div class="dropdown-item noti-title">
                <h5 class="m-0">Notificações</h5>
            </div>

            <div class="dropdown-item notify-item" v-if="this.notifications.length === 0">
                <p class="notify-details"> Nennhuma notificação </p>
            </div>

            <perfect-scrollbar v-else>
                <a :href="notification.action" class="dropdown-item notify-item" v-for="(notification, index) in this.notifications">
                    <div class="notify-icon" :class="notification.color">
                        <i :class="notification.icon" v-if="notification.icon"></i>
                        <img :src="notification.thumbnail" v-if="notification.thumbnail" alt="" class="img-fluid rounded-circle">
                    </div>
                    <p class="notify-details" :class="{'new-notify': notification.new}">
                        {{ notification.text }}
                        <small class="text-muted">Há {{ timeDiff(index) }}</small>
                    </p>
                </a>
            </perfect-scrollbar>

            <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item notify-all">
                Ver todas
            </a>

            <a href="javascript:void(0);" @click="testNotification" class="dropdown-item text-center text-primary notify-item notify-all">
                Notificação de teste
            </a>

        </div>
    </li>
</template>

<script>
    import {PerfectScrollbar} from 'vue2-perfect-scrollbar'
    import moment from 'moment'
    import 'moment/locale/pt-br'
    import {exceptionError, toSeek, sendAlert} from "../../vendor/common"

    moment.locale('pt-BR')

    export default {
        name: 'notifications',
        props: ['user'],
        data() {
            return {
                has_new: false,
                info_new: false,
                now: moment(),
                count: 0,
                notifications: []
            }
        },
        methods: {
            checkPermission() {
                if (Notification.permission === "default") {
                    this.$notification.requestPermission().then(
                        (response) => {
                            if (response === "granted") this.notify({
                                title: 'Obrigado por habilitar as notificações',
                                text: 'Agora você verá esse aviso sempre que o e-PRO tiver alguma novidade pra você! Basta manter uma aba aberta e logada no sistema.',
                                action: '#'
                            })
                        }
                    )
                }
            },

            notify(notification) {
                notification.new = true
                this.notifications.unshift(notification)
                this.has_new = true
                this.count++

                if (Notification.permission === "granted") {
                    this.$notification.show(notification.title,
                        {
                            body: notification.text,
                            lang: 'pt-BR',
                            icon: `${process.env.MIX_AWS_S3_ENDPOINT}/assets/images/projevi/icon.jpg`,
                        },
                        {
                            onclick: (e) => {
                                e.preventDefault()
                                document.location = notification.action
                            }
                        })
                } else if (Notification.permission === "denied") {
                    sendAlert({
                        title: notification.title,
                        message: notification.text,
                        position: 'bottom-right',
                        type: 'info',
                        time: 5000
                    })
                }
            },

            timeDiff(index) {
                let sent_at = this.notifications[index].sent_at

                return this.notifications[index].sent_at_humanize = moment.duration(this.now.diff(sent_at)).humanize()
            },

            clearAlert() {
                setTimeout(() => this.markAsRead(), 1000)
            },

            markAsRead() {
                this.now = moment()
                this.has_new = false
                this.count = 0
                this.notifications.forEach(item => item.new = false)
            },

            testNotification() {
                toSeek(`${process.env.MIX_APP_URL}/test`, null, {Accept: 'application/json'}).then();
            }
        },
        components: {
            PerfectScrollbar
        },
        mounted() {
            toSeek(`${process.env.MIX_APP_URL}/api/notifications/${this.user}`, null, {Accept: 'application/json'}).then(
                (response) => {
                    response.data.forEach((item) => {
                        let result = item.attributes
                        result.id = item.id

                        if (item.attributes.new) {
                            this.has_new = true
                            this.count++
                        }

                        this.notifications.push(result)
                    })
                }
            ).catch(error => exceptionError(error))

            this.checkPermission()

            if(process.env.MIX_ECHO_SERVER_ENABLE === "true") {
                Echo.private('users.' + this.user).notification((notification) => this.notify(notification))
            }
        }
    }
</script>

<style src="vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css"/>
<style>
    .ps {
        max-height : 55vh;
    }

    .new-notify {
        border-right : 3px solid #FA5C7C
    }
</style>
