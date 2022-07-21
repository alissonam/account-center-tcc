<template>
  <div class="login-page row items-center justify-center">
    <div class="col-xs-12 col-sm-7 col-md-5 col-lg-4">
      <div class="row items-center justify-center">
        <div class="image-container">
          <img
            src="~assets/logo.jpeg"
            alt="Logo image"
            style="max-height:90px;"
          >
        </div>
      </div>
      <login-form
        v-if="!showForgotPassword"
        @show-forgot-password="showForgotPassword = true"
      />
      <forgot-password-form
        v-if="showForgotPassword"
        @hide-forgot-password="showForgotPassword = false"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import LoginForm from 'components/Login/LoginForm'
import ForgotPasswordForm from 'components/Login/ForgotPasswordForm'

let showForgotPassword = ref(false)
</script>
<style scoped>
.login-page {
  min-height: 100vh;
  min-width: 100vw;
  padding: 20px 20px 5px 20px;
  background: rgb(0, 102, 153);
  background: linear-gradient(#98c5de 0%, #0a457d 100%);
  display: flex;
}

.image-container {
  background: #d3dee5;
  border-radius: 15px;
  margin-bottom: 15px;
  padding: 10px;
  z-index: 2;
}
</style>
