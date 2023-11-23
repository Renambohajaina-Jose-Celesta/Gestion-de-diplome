<template>
  <div>
    <div class="header" style="background-color:  rgba(99, 122, 224, 0.609); width: 100%; height: 20vh; position: ;">
      <div class="text-black" style="text-align: left;">
        <h2>VERIFIER</h2>
      </div>
      <div class="form-group d-flex">
        <p class="mt-2">Par <span>:</span> </p>
        <div class="mr-2">
          <input type="text" v-model="registrationQuery" @input="activeInput = 'registration'"
            class="bg-gray-200 focus:bg-white border border-gray-300 rounded-md py-1 px-3 w-26 mr-1"
            placeholder="Numéro d'inscription" style="height: 45px;" />
        </div>
        <p class="mt-2">ou <span>:</span> </p>
        <div class="mr-2">
          <input type="text" v-model="nameQuery" @input="activeInput = 'name'"
            class="bg-gray-200 focus:bg-white border border-gray-300 rounded-md py-1 px-3 w-26 mr-1"
            placeholder="Nom ou prénom" style="height: 45px;" />
        </div>
        <div>
          <button @click="search" class="btn btn-primary" style="height: 45px;">
            <i class="fa fa-search"></i> Rechercher
          </button>
        </div>
      </div>
    </div>
    <!--  -->
    <div class="col-lg-12 grid-margin stretch-card px-2" style="position: relative; z-index: 1; margin-top: 20px;">
      <div class="card">
        <div class="card-body">
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex"
            style="background-color: rgba(161, 231, 198, 0.224); border-radius: 10px;">
            <div class="d-flex justify-content-between align-items-center shadow-success pt-4 pb-3">
              <h6 class="text-black text-capitalize ps-3">
                Liste des Diplômes Vérifiés
              </h6>
            </div>
          </div>
          <div class="table-responsive pt-3 px-[20px]">
            <table class="table table-hover">
              <thead class="table-dark">
                <tr>
                  <th>ID</th>
                  <th>N° Insc</th>
                  <th>Nom</th>
                  <th>Prénom</th>
                  <!-- <th>Date Naiss.</th> -->
                  <!-- <th>Lieu Naiss.</th> -->
                  <!-- <th>Libelle Court</th> -->
                  <th>Série</th>
                  <th>Secteur</th>
                  <th>Spécialité</th>
                  <th>Année</th>
                  <!-- <th>Mention</th> -->
                  <!-- <th>N° Demande</th> -->
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="diplome in diplomes" :key="diplome.id">
                  <td class="align-middle">{{ diplome.id }}</td>
                  <td class="align-middle">{{ diplome.numCandidat }}</td>
                  <td class="align-middle">{{ diplome.nomCandidat }}</td>
                  <td class="align-middle">{{ diplome.prenomCandidat }}</td>
                  <!-- <td class="align-middle">{{ formatDate(diplome.dateNaissance) }}</td> -->
                  <!-- <td class="align-middle">{{ diplome.lieuNaissance }}</td> -->
                  <!-- <td>{{ diplome.libelleCourt }}</td> -->
                  <td class="align-middle">{{ diplome.libelleSerie }}</td>
                  <td class="align-middle">{{ diplome.libelleSecteur }}</td>
                  <td class="align-middle">{{ diplome.nomSpecialite }}</td>
                  <td class="align-middle">{{ diplome.annee }}</td>
                  <!-- <td class="align-middle">{{ diplome.mention }}</td> -->
                  <!-- <td>{{ diplome.numDemande }}</td> -->
                  <td class="align-middle">
                    <button @click="ouvrirModalImprimer(diplome)" class="btn btn-primary"><i
                        class="fa fa-eye"></i></button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>


    <!-- Modal d'impression -->
    <div class="modal" :class="{ 'is-active': afficherModalImprimer }">
      <div class="modal-background"></div>
      <div class="modal-content">
        <!-- Contenu de la modal d'impression -->
        <form @submit.prevent="imprimerDiplome" class="">
          <!-- Utilisez des balises span pour afficher les informations -->
          <div class="custom-modal-body mr-7">
            <div class="flex-container">
              <div class="label form-control" style="border: 0">N° Insc :</div>
              <div class="data form-control" style="border: 0; text-align: left;">
                {{ num }}
              </div>
              <div class="label form-control" style="border: 0">Nom :</div>
              <div class="data form-control" style="border: 0; text-align: left;">
                {{ nom }}
              </div>
            </div>

            <div class="flex-container">
              <div class="label form-control" style="border: 0">Prénom :</div>
              <div class="data form-control" style="border: 0; text-align: left;">
                {{ prenom }}
              </div>
              <div class="label form-control" style="border: 0">Né (é) le :</div>
              <div class="data form-control" style="border: 0; text-align: left;">
                {{ formatDateLong(dateNaissance) }} à {{ lieuNaissance }}
              </div>
            </div>

            <div class="flex-container">
              <div class="label form-control" style="border: 0">Serie :</div>
              <div class="data form-control" style="border: 0; text-align: left;">
                {{ libelleSerie }}
              </div>
                <div class="label form-control" style="border: 0">Secteur :</div>
              <div class="data form-control" style="border: 0; text-align: left;">
                {{ libelleSecteur }}
              </div>
            </div>

            <div class="flex-container">
              <div class="label form-control" style="border: 0">Specialite :</div>
              <div class="data form-control" style="border: 0; text-align: left;">
                {{ nomSpecialite }}
              </div>
              <div class="label form-control" style="border: 0">Année :</div>
              <div class="data form-control" style="border: 0; text-align: left;">
                {{ annee }}
              </div>
            </div>

            <div class="flex-container">
              <div class="label form-control" style="border: 0">Mention :</div>
              <div class="data form-control" style="border: 0; text-align: left;">
                {{ mention }}
              </div>
              <div class="form-group">
                <label for="dateSession">Date du session :</label>
                <input type="text" v-model="dateSession" class="form-control" id="dateSession"
                  placeholder="Date du session" required="">
              </div>
            </div>

            <div class="flex-container">
              <div class="form-group">
                <label for="numOrdre">N° d'ordre :</label>
                <input type="text" v-model="numOrdre" class="form-control" id="numOrdre" placeholder="Numéro d'ordre" required="">
              </div>
              <div class="form-group">
                <label for="numMatrice">N° matrice :</label>
                <input type="text" v-model="numMatrice" class="form-control" id="numMatrice" placeholder="Numéro matrice" required="">
              </div>
            </div>
          </div>
          <!-- Ajoutez d'autres balises span en fonction de vos données -->
          <div class="custom-modal-footer ml-auto">
            <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-print"></i></button>
          </div>
        </form>
        <div class="custom-modal-footer ml-auto">
          <button @submit.prevent="imprimerDiplome" type="" class="btn btn-primary btn-lg"><i class="fa fa-print"></i></button>
          <button @click="fermerModalImprimer" class="btn btn-secondary btn-lg">Annuler</button>
        </div>
      </div>
    </div>

  </div>
</template>
  
<script>
import axios from "axios";
import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";

pdfMake.vfs = pdfFonts.pdfMake.vfs;

export default {
  data() {
    return {
      diplomes: [],
      afficherModalImprimer: false,
      centre: "",
      date: "",
      num: "",
      nom: "",
      prenom: "",
      dateNaissance: "",
      lieuNaissance: "", // Assurez-vous que cette variable est définie
      libelleSerie: "", // Assurez-vous que cette variable est définie
      libelleSecteur: "",
      nomSpecialite: "", // Assurez-vous que cette variable est définie
      mention: "", // Assurez-vous que cette variable est définie
      numerodemande: "", // Assurez-vous que cette variable est définie
      annee: "", // Assurez-vous que cette variable est définie
      numCandidat: "", // Assurez-vous que cette variable est définie
      nomCandidat: "", // Assurez-vous que cette variable est définie
      prenomCandidat: "", // Assurez-vous que cette variable est définie
      dateSession: "",
      numOrdre: "",
      numMatrice: "",
    };
  },
  mounted() {
    this.chargerDiplome();
  },
  methods: {
    formatDate(dateString) {
      if (!dateString) return '';
      const options = { day: '2-digit', month: 'short', year: 'numeric' };
      const formattedDate = new Date(dateString).toLocaleDateString('fr-FR', options);
      return formattedDate;
    },
    formatDateLong(dateString) {
      if (!dateString) return '';
      const options = { day: '2-digit', month: 'long', year: 'numeric' };
      const formattedDate = new Date(dateString).toLocaleDateString('fr-FR', options);
      return formattedDate;
    },

    async chargerDiplome() {
      try {
        const response = await axios.get("http://localhost/applicationjose/src/api/insert_verifier.php");

        if (response.status === 200) {
          this.diplomes = response.data;
        } else {
          console.error("Erreur lors de la récupération des données. Réponse du serveur :", response.status);
        }
      } catch (error) {
        console.error("Erreur lors de la requête HTTP :", error);
      }
    },
    ouvrirModalImprimer(diplome) {
      this.centre = "";
      this.dateNaissance = diplome.dateNaissance;
      this.num = diplome.numCandidat;
      this.nom = diplome.nomCandidat;
      this.prenom = diplome.prenomCandidat;
      this.lieuNaissance = diplome.lieuNaissance;
      this.lieuNaissance = diplome.lieuNaissance;
      this.libelleSerie = diplome.libelleSerie;
      this.libelleSecteur = diplome.libelleSecteur;
      this.nomSpecialite = diplome.nomSpecialite;
      this.annee = diplome.annee;
      this.mention = diplome.mention;
      this.afficherModalImprimer = true;
    },
    fermerModalImprimer() {
      this.dateSession = '';
      this.numOrdre = '';
      this.numMatrice = '';
      this.afficherModalImprimer = false;
    },

    // Fonction pour charger l'image en tant que dataURL
    async getImageDataURL(imagePath) {
      try {
        const response = await axios.get(imagePath, { responseType: 'arraybuffer' });
        const dataURL = `data:image/jpeg;base64,${Buffer.from(response.data).toString('base64')}`;
        return dataURL;
      } catch (error) {
        console.error('Erreur lors du chargement de l\'image en tant que dataURL:', error);
        return null;
      }
    },

    imprimerDiplome() {
      // Stockez les valeurs dans des variables locales pour éviter la répétition
      const num = this.num;
      const nom = this.nom;
      const prenom = this.prenom;
      const dateNaissance = this.formatDateLong(this.dateNaissance);
      const lieuNaissance = this.lieuNaissance;
      const libelleSerie = this.libelleSerie;
      const nomSpecialite = this.nomSpecialite;
      const libelleSecteur = this.libelleSecteur;
      const mention = this.mention;
      // Ajoutez les champs manquants (dateSession, numOrdre, numMatrice)
      const dateSession = this.dateSession; // Assurez-vous que cette valeur est correctement définie dans votre modèle Vue
      const numOrdre = this.numOrdre; // Assurez-vous que cette valeur est correctement définie dans votre modèle Vue
      const numMatrice = this.numMatrice; // Assurez-vous que cette valeur est correctement définie dans votre modèle Vue

      // const d = new Date();
      // d.toDateString();

      const options = { day: '2-digit', month: 'long', year: 'numeric' };
      const d = new Date().toLocaleDateString('fr-FR', options);



      // Définition du document PDF
      const documentDefinition = {

        content: [
          { text: 'REPOBLIKAN\'I MADAGASIKARA', style: 'header' },
          { text: 'Fitiavana - Tanaindrazana - Fandrosoana', style: 'header' },
          { text: 'DIPLÖME DE BACHELIER DE L\'ENSEIGNEMENT (technique ou generale)', style: 'header' },
          { text: 'Le Ministre de l\'Enseinement Supeérieur et de la Recherche Scientifique, et par délégation le Président de l\'Université de Toliara,', style: 'subheader1' },
          { text: 'Vu l\'ésemble des textes en vigueur potant l\'organisation du Baccalaureat de l\'Enseignement Secondaire, de l\'Enseignement Technique et Technologique et de l\'Enseignement Professionnel,', style: 'subheader1' },
          { text: 'Vue de Procès-Verbale de l\'examen du Baccalaureat de l\'Enseinement Secondaire en date du (date) à Toliara, dressé par le Presidant du Jury de la série (technique ou generale)', style: 'subheader1' },

          { text: `Confier à :`, style: 'subheader' },
          { text: `Nom: ${nom} ${prenom}`, style: 'nom' },
          { text: `Né(e) le: ${dateNaissance} à ${lieuNaissance}`, style: 'ne' },
          { text: `Inscrit (e) sous le numéro: ${num} à la session du ${dateSession}`, style: 'subheader' },
          { text: `Le Grande de BACHELIER DE L\`ENSEINEMENT (technique ou generele) Option : ${libelleSerie}`, style: 'subheader' },
          { text: `Secteur: ${libelleSecteur}                      Spécialité: ${nomSpecialite}`, style: 'subheader' },

          { text: `Mention: ${mention} pour en jouir avec les droits et prérogation qui y sont attachés`, style: 'subheader' },
          { text: `Toliara, le ${d}`, style: 'subheader' },
          { text: `Le Président,`, style: 'subheader' },
          { text: 'L\'impetrant', style: 'subheader' },
          { text: `NUMERO D ORDRE : ${numOrdre}/${numMatrice}`, style: 'subheader' },
          { text: 'MINISTER DE L\'ENSEINEMENT SUPERIEUR ET DE LA RECHERCHE SCIENTIFIQUE', style: 'header' },
        ],
        styles: {
          header: {
            fontSize: 18,
            bold: true,
            alignment: 'center',
            margin: [0, 0, 0, 10],
            marginTop: 1,
          },
          nom: {
            fontSize: 18,
            bold: true,
            alignment: 'center',
            margin: [0, 0, 0, 10],
          },
          ne: {
            fontSize: 18,
            bold: true,
            alignment: 'center',
            margin: [0, 0, 0, 10],
          },
          subheader1: {
            fontSize: 9,
            margin: [0, 0, 0, 10],
          },
          subheader: {
            fontSize: 14,
            margin: [0, 10, 0, 5],
          },
        },
        // Ajoutez cette propriété pour définir l'orientation en paysage
        pageOrientation: 'landscape',
        pageMargins: [12.7, 12.7, 12.7, 12.7], // [Haut, Droite, Bas, Gauche]
      };

      // Créez le PDF
      const pdfDoc = pdfMake.createPdf(documentDefinition);

      // Téléchargez le PDF (si nécessaire)
      pdfDoc.download(`Diplome_Bac_${num}_${nom}_${prenom}.pdf`);

      // Fermez la modal ou effectuez d'autres actions nécessaires
      this.fermerModalImprimer();
    },



  },
};
</script>

<style scoped>
/* .card {
    box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.278);
    border-radius: 0.8rem;
  } */


.bg-label-primary {
  background-color: #44f856;
  color: #38f955d0;
}

.me-1 {
  margin-right: 0.25rem;
}

.badge {
  text-transform: uppercase;
  line-height: 0.75;
}

.badge {
  /* display: inline-block; */
  margin-top: 15px;
  margin-left: 4px;
  padding: 0.52em 0.593em;
  font-size: 0.8125em;
  font-weight: 500;
  /* line-height: 1; */
  color: #fff;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: 0.25rem;
}

.bg-label-waiting {
  background-color: #ff0000cb;
  color: #fc2200d0;
}

.me-1 {
  margin-right: 0.25rem;
}

.badge-waiting {
  display: inline-block;
  margin-top: 14px;
  margin-left: 3px;
  /* padding: 0.52em 0.593em; */
  font-size: 0.8125em;
  font-weight: 500;
  line-height: 1;
  color: #fff;
  text-align: center;
  /* white-space: nowrap; */
  /* vertical-align: baseline; */
  border-radius: 0.25rem;
}

.flex-container {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
}

.label {
  flex: 1;
  font-weight: bold;
  font-size: 18px;
}

.data {
  flex: 2;
  font-size: 17px;
}

.modal {
  background-color: rgba(0, 0, 0, 0);
  display: none;
  align-items: center;
  justify-content: center;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}

.modal.is-active {
  display: flex;
}

.modal-background {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.412);
}

.modal-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.496);
  max-width: 900px;
  width: 100%;
}

.modal-close {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 1.5rem;
  background-color: transparent;
  border: none;
  color: #000;
  cursor: pointer;
}

.rotate-icon {
  animation: rotate 1s infinite linear;
}

@keyframes rotate {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

/* Classe pour l'icône animée avec zoom avant */
.zoom-icon {
  animation: zoom 2s infinite ease-in-out;
  transform-origin: center;
}

@keyframes zoom {
  0% {
    transform: scale(1);
  }

  50% {
    transform: scale(1.5);
  }

  100% {
    transform: scale(1);
  }
}

/* Classe pour l'icône animée avec rebond */
.bounce-icon {
  animation: bounce 2s infinite ease-in-out;
}

@keyframes bounce {

  0%,
  20%,
  50%,
  80%,
  100% {
    transform: translateY(0);
  }

  40% {
    transform: translateY(-30px);
    /* Ajustez la hauteur du rebond */
  }

  60% {
    transform: translateY(-15px);
    /* Ajustez la hauteur du rebond */
  }
}

.table-container {
  max-height: 381px;
  overflow-y: auto;
}
</style>
  