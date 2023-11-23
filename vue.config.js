// const { defineConfig } = require('@vue/cli-service')
// module.exports = defineConfig({
//   transpileDependencies: true
// })

const { defineConfig } = require('@vue/cli-service');

module.exports = defineConfig({
  transpileDependencies: ['vuetify'], // Remplacez 'vuetify' par le nom de vos dépendances à transpiler
  publicPath: process.env.NODE_ENV === 'production' ? '/Gestion-de-diplome/' : '/'
});



