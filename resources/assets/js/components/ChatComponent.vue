<template>
  <div>
      <ul>
        <li v-for="message in messages">{{ message.body }} </li>
      </ul>
      <input type="text" v-model="newMessage" @keydown="tapParticipants">
      <button @click="send">Enviar</button>
      <span v-if="activePeer">User {{ activePeer.name }} is typing!</span>

      Active users:
      <ul>
        <li v-for="activeUser in activeUsers">{{ activeUser.name }}</li>
      </ul>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        activeUsers: null,
        activePeer: false,
        newMessage: '',
        messages: [
          { body: 'Missatge 1'},
          { body: 'Missatge 2'},
          { body: 'Missatge 3'}
        ]
      }
    },
    methods: {
      send() {
        axios.post('/chat_message',{
          'body': this.newMessage
        })
      },
      tapParticipants() {
        Echo.private('new-message')
          .whisper('typing', {
            name: window.user.name
          })
      }
    },
    mounted() {
      console.log('Component mounted.')
      Echo.join('new-message')
        .here((users) => {
          console.log(users.name)
          this.activeUsers = users
        })
        .joining((user) => {
          console.log('new user: ' + user.name)
          this.activeUsers.push(user)
        })
        .leaving((user) => {
          console.log(user.name + ' leaved')
          const foundUser = this.activeUsers.find((u) => {
            return u.id === user.id;
          })
          this.activeUsers.splice(this.activeUsers.indexOf(foundUser),1)
        })
        .listen('ChatMessage', (event) => {
          console.log('He rebut un nou event de broadcast')
          console.log(event);
          this.messages.push({ body: event.message})
        })
        .listenForWhisper('typing', (e) => {
          this.activePeer = {
            name: e.name
          };
        })
    }
  }
</script>