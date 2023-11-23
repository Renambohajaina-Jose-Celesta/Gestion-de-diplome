<!-- HomePage.vue -->

<template>
  <div class="w-screen h-screen flex">
    <!-- Side bar -->
    <div class="w-[180px] h-full bg-gray-200 text-white" v-show="showSide">
      <!-- ... (contenu du sidebar) ... -->
      <div class="h-[50px] bg-white flex justify-start items-center" style="border-radius: ;">
        <div class="items-center justify-start px-[20px] flex ml-[69px]">
          <img src="../../assets/univ.png" alt="" class="items-center justify-start h-[49px] mt-[1px]" />
          <h3 class="text-xl mt-[9px]" style="color: #ff6666;"></h3>
        </div>
      </div>
      <div class="h-[calc(100vh-50px)] bg-gray-800 py-[20px]">
        <div class="flex fex-col justify-between h-full px-[20px] space-y-[10px]">
          <div class="flex flex-col justify-between space-y-[10px]">
            <div class="h-full">
              <!-- Router Links -->
              <router-link to="/DashboardDiplome" class="router-link h-[50px]"
                :class="{ 'active-link': $route.name === 'DashboardDiplome' }">
                <i class="fas fa-home fa-lg mr-2" style="font-size: 20px"></i>
                <span class="text-lg">DASHEBOARD</span>
              </router-link>
              <router-link to="/ListesEtudiants" class="router-link h-[50px]"
                :class="{ 'active-link': $route.name === 'ListesEtudiants' }">
                <i class="fas fa-school fa-lg mr-2" style="font-size: 20px"></i>
                <span class="text-lg">ETUDIANTS</span>
              </router-link>
              <router-link to="/DemandesDiplome" class="router-link h-[50px]"
                :class="{ 'active-link': $route.name === 'DemandesDiplome' }">
                <i class="fas fa-file-alt fa-lg mr-2" style="font-size: 20px"></i>
                <span class="text-lg">DEMANDES</span>
              </router-link>
              <router-link to="/ControlerDiplome" class="router-link h-[50px]"
                :class="{ 'active-link': $route.name === 'ControlerDiplome' }">
                <i class="fas fa-cogs fa-lg mr-2" style="font-size: 20px"></i>
                <span class="text-lg">CONTROLER</span>
              </router-link>
              <router-link to="/SerieDeDiplome" class="router-link h-[50px]"
                :class="{ 'active-link': $route.name === 'SerieDeDiplome' }">
                <i class="fas fa-cut fa-lg mr-2" style="font-size: 20px"></i>
                <span class="text-lg">SERIE</span>
              </router-link>
              <router-link to="/SortieDiplome" class="router-link h-[50px]"
                :class="{ 'active-link': $route.name === 'SortieDiplome' }">
                <i class="fas fa-copy fa-lg mr-2" style="font-size: 20px"></i>
                <span class="text-lg">DUPLICATION</span>
              </router-link>
              <router-link to="/DechargeDiplome" class="router-link h-[50px]"
                :class="{ 'active-link': $route.name === 'DechargeDiplome' }">
                <i class="fas fa-download fa-lg mr-2" style="font-size: 20px"></i>
                <span class="text-lg">DECHARGE</span>
              </router-link>
            </div>
            <div class="h-[50px]">
              <div>
                <router-link to="/logout" class="router-link" :class="{ 'active-link': $route.name === '' }">
                  <i class="fas fa-sign-out-alt fa-lg mr-2" style="font-size: 20px"></i>
                  <span class="text-lg">Deconnection</span>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Main content -->
    <div class="w-full h-full bg-gray-800">
      <div class="h-[50px] bg-gray-100 flex items-center shadow-sm px-[20px] w-full py-[10px] z-10 border-b">
        <!-- ... (contenu de la barre principale) ... -->
        <!-- Hambuger menu -->
        <div class="cursor-pointer w-[30px]" @click="toggleSideBar">
          <i class="fas fa-bars"></i>
          <!-- Icône de menu (barres horizontales) -->
        </div>
        <!-- Recherche bar -->
        <div class="w-full ml-4">
          <!-- <input
            type="text"
            placeholder="Rechercher..."
            class="bg-gray-200 focus:bg-white border border-gray-300 rounded-md py-1 px-3 w-50"
          /> -->
          <!-- <a class="navbar-brand" href="#">{{ routeName }}</a> -->
        </div>
        <!-- User login -->
        <div class="w-[50px] ml-auto">
          <div class="flex items-center justify-start space-x-4 cursor-pointer" @click="toggleDrop">
            <img src="../../assets/image.jpg" alt="" class="w-9 h-9 rounded-full" />
            <!-- <div class="font-semibold dark:text-black text-left">
              
            </div> -->
          </div>
          <!-- Drop down -->
          <div v-show="showDropDown"
            class="absolute right-[10px] z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-gray-300">
            <div class="py-1 text-left" role="none">
              <a href="#" class="text-black-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1"
                id="menu-item-0"><div class="text-xs text-gray-500 dark:text-gray-400">Jose, Celestin</div>
              <div class="text-xs text-gray-500 dark:text-gray-400">Admin</div></a>
              <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1"
                id="menu-item-1">Support</a>
              <a href="#" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1"
                id="menu-item-2">License</a>
              <form @submit.prevent="logout" role="none">
                <button type="submit" class="text-gray-700 block w-full px-4 py-2 text-left text-sm" role="menuitem"
                  tabindex="-1" id="menu-item-3">
                  Logout
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="h-[calc(100vh-50px)] bg-gray-50">
        <div class="border border-gray-300 rounded-md  h-full overflow-y-auto">
          <router-view></router-view>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      showDropDown: false,
      showSide: true,
    };
  },
  methods: {
    // Masquer/afficher la barre latérale
    toggleSideBar() {
      this.showSide = !this.showSide;
    },

    // Afficher/masquer le menu utilisateur
    toggleDrop() {
      this.showDropDown = !this.showDropDown;
    },
  },

};
</script>
<style scoped>
/* Styles pour ce composant */
.router-link {
  display: inline-flex;
  position: relative;
  /* Correction : utilisez "position" au lieu de "relative" */
  align-items: center;
  /* Correction : utilisez "align-items" pour centrer les éléments */
  padding: 10px;
  width: 100%;
  margin-left: 0;
  /* Ajoutez cette ligne pour définir la marge gauche à 0 */
  font-size: small;
}

.icon {
  margin-right: 8px;
  width: 25px;
  height: 25px;
  fill: currentColor;
}

/* Styles pour ce composant */
.router-link {
  display: inline-flex;
  position: relative;
  align-items: center;
  padding: 10px;
  width: 115%;
  margin-left: -20px;
  border-bottom-left-radius: 10px;
  border-top-left-radius: 10px;
  font-size: small;
  text-decoration: none;
  /* Supprime le soulignement du lien par défaut */
  color: rgb(255, 255, 255);
  /* Couleur de texte par défaut */
  transition: background-color 0.3s;
  /* Animation de transition de couleur de fond */
  cursor: pointer;
  /* Définit le curseur sur "pointer" au survol */
}

a {
  text-decoration: none;
  
}

/* Ajoutez le style pour le lien actif */
.active-link {
  background-color: rgb(255, 255, 255);
  color: rgb(0, 0, 0);
}

/* Style pour le lien au survol */
.router-link:hover {
  background-color: rgb(255, 255, 255);
  /* Change la couleur de fond lorsque le curseur survole le lien */
  color: rgb(0, 0, 0);
  /* Change également la couleur du texte pour assurer la lisibilité */
}



.sidebar .nav:not(.sub-menu)>.nav-item {
  border-bottom: 1px solid #f3f3f3;
  margin-top: 0;
}

.sidebar .nav .nav-item {
  -webkit-transition-duration: 0.25s;
  -moz-transition-duration: 0.25s;
  -o-transition-duration: 0.25s;
  transition-duration: 0.25s;
}

ul li,
ol li,
dl li {
  line-height: 1.8;
}

a {
  color: rgb(29, 27, 27);
}
</style>
