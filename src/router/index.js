import { createRouter, createWebHistory } from 'vue-router';
import AuthService from '../pages/AuthService.js'; // Remplacez le chemin d'accès par le chemin réel de votre service

import HomePage from '../pages/master/HomePage.vue'; 
import ListesEtudiants from '../pages/ListesEtudiants.vue';
import DemandesDiplome from '../pages/DemandesDiplome.vue';
import DashboardDiplome from '../pages/DashboardDiplome.vue'; 
import ControlerDiplome from '../pages/ControlerDiplome.vue'; 
import SerieDeDiplome from '../pages/SerieDeDiplome.vue'; 
import SortieDiplome from '../pages/SortieDiplome.vue'; 
import DechargeDiplome from '../pages/DechargeDiplome.vue'; 
import LoginPage from '../pages/LoginPage.vue';


const routes = [
  {
    path: '/',
    redirect: '/LoginPage', // Rediriger vers la page de connexion par défaut
  },
  {
    path: '/HomePage',
    component: HomePage,
    redirect: "",
    meta: { requiresAuth: true },
    children: [
      {
        path: '/DashboardDiplome',
        component: DashboardDiplome,
        name: 'DashboardDiplome',
      },
      {
        path: '/ListesEtudiants',
        component: ListesEtudiants,
        name: 'ListesEtudiants',
      },
      {
        path: '/DemandesDiplome',
        component: DemandesDiplome,
        name: 'DemandesDiplome',
      },
      {
        path: '/ControlerDiplome',
        component: ControlerDiplome,
        name: 'ControlerDiplome',
      },
      {
        path: '/SerieDeDiplome',
        component: SerieDeDiplome,
        name: 'SerieDeDiplome',
      },
      {
        path: '/SortieDiplome',
        component: SortieDiplome,
        name: 'SortieDiplome',
      },
      {
        path: '/DechargeDiplome',
        component: DechargeDiplome,
        name: 'DechargeDiplome',
      },
    ],
  },
  {
    path: '/LoginPage',
    component: LoginPage,
    name: 'LoginPage',
  },
  {
    path: '/logout',
    name: 'Logout',
    component: () => import(/* webpackChunkName: "logout" */ '../pages/LogoutPage.vue'),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});


// Ajoutez votre guard de navigation
router.beforeEach((to, from, next) => {
  console.log('Navigation guard - De:', from.fullPath, 'Vers:', to.fullPath);
  
  // Votre logique de guard ici
  if (to.meta.requiresAuth && !AuthService.isAuthenticated()) {
    console.log('Redirection vers /LoginPage car l\'authentification est requise.');
    // Redirigez vers la page de connexion si l'authentification est requise
    next('/LoginPage');
  } else if (to.name === 'LoginPage' && AuthService.isAuthenticated()) {
    console.log('Redirection vers /DashboardDiplome car l\'utilisateur est déjà authentifié.');
    // Si l'utilisateur est déjà authentifié et essaie d'accéder à la page de connexion, redirigez-le
    next('/DashboardDiplome');
  } else {
    // Continuez avec la navigation
    console.log('Continuer avec la navigation.');
    next();
  }
});



export default router;