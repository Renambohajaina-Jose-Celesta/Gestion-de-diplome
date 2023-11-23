<template>
  <navbar btnBackground="bg-gradient-success" />
  <div class="page-header align-items-start min-vh-100">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container my-auto">
      <div class="row">
        <div class="col-lg-4 col-md-8 col-12 mx-auto">
          <div class="card z-index-0 fadeIn3 fadeInBottom">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1">
                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">
                  Crée un compte
                </h4>
                <div class="row mt-3">
                  <div class="col-2 text-center ms-auto">
                  </div>
                  <div class="col-2 text-center px-1">
                  </div>
                  <div class="col-2 text-center me-auto">
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <form role="form" class="text-start mt-3">
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="Nom">
                </div>
                <div class="mb-3">
                  <input type="email" class="form-control" placeholder="Email">
                </div>
                <div class="mb-3">
                  <input type="password" class="form-control" placeholder="Mot de passe">
                </div>

                <div class="text-center">
                  <material-button class="my-4 mb-2" variant="gradient" color="success" fullWidth
                    v-on:click="SignUp">Crée</material-button>
                </div>
                <p class="mt-4 text-sm text-center">
                  Je deja un compte.
                  <router-link :to="{ name: 'SignIn' }"
                    class="text-success text-gradient font-weight-bold">Connection</router-link>
                </p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>


export default {
  name: "sign-up",
  data() {
    return {
      nom: '',
      email: '',
      password: ''

    }
  },
  components: {

  },

};
</script>
