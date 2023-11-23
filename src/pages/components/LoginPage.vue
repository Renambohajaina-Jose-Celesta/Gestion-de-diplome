<!-- <template>
  <div>
    <div class="container">
      <div class="row">
        <div class="col-md-4 ml-auto mr-auto">
          <div class="card my-4">
            <div class="card-heading">
              <h4 class="h card-title bg-info p-3 text-center">Admin Login</h4>
            </div>
            <div class="card-body">
              <form action="" method="POST">
                <div class="form-row">
                  <div class="form-group col-md-12">
                    <label for="User">Nom d'utilisateur</label>
                    <input type="text" placeholder="Nom d'utilisateur" id="User" class="form-control" name="nom" v-model="user.nom">
                  </div>
                  <div class="form-group col-md-12">
                    <label for="User">Mot de passe</label>
                    <input type="password" placeholder="Mot de passe" id="Mdp" class="form-control" name="password" v-model="user.password">
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-10">
                    <button class="btn btn-success btn-sm float-right btn-block" @click.prevent="onLogin()">Connection</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
  
<script>
import axios from 'axios';

export default {
  // name: 'LoginPage',
  data() {
    return {
      user: {
        nom: null,
        password: null
      }
    }
  },
  methods: {
    async onLogin() {
      try {
        const formData = new FormData();
        formData.append('nom', this.user.nom);
        formData.append('password', this.user.password);
        
        // Utilisation de la syntaxe async/await pour les requêtes Axios
        const res = await axios.post("http://localhost/vue-paper-dashboard-master/src/api/api.php?action=login", formData);
        
        if (res.data.error) {
          console.log("Error connection", res.data.message);
          alert(res.data.message);
        } else {
          console.log("Success connection", res.data.message);
          alert(res.data.message);
          this.$router.push("/dashboard");
        }
      } catch (err) {
        console.log("Error", err);
      }
    }
  }
}
</script>
<style></style> -->
