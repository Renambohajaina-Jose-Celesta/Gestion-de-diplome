<template>
               <!-- ListesEtudiants.vue -->
  <div>
    <div class="header"
      style="background-color: rgba(99, 122, 224, 0.609); width: 100%; height: 20vh; position: relative; border-bottom-left-radius: 15px;">
      <h2 style="text-align: left;">ETUDIANTS</h2>

      <div class="form-group flex">
        <input type="file" ref="fileInput" class="form-control ml-3" accept=".xlsx, .xls"
          style="width: 403px; height: 40px;" />
        <button @click="uploadExcel" class="btn btn-primary ml-1" style="height: 40px;">Importer</button>
        <div class="" style="margin-left: auto;">
          <input type="text" v-model="searchQuery" style="height: 45px;"
            class="bg-gray-200 focus:bg-white border border-gray-300 rounded-md py-1 px-3 w-[150px] mr-1"
            placeholder="N° d'inscription" />
          <input type="year" v-model="searchYear" style="height: 45px;"
            class="bg-gray-200 focus:bg-white border border-gray-300 rounded-md py-1 px-3 w-25" placeholder="Année" />
          <button @click="searchStudent" class="btn btn-primary ml-1" style="height: 45px;">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </div>


    <!-- Barre de progression -->
    <div class="progress-container" v-if="showProgressBar">
      <span class="ml-60">
        <svg>
          <circle cx="70" cy="70" r="70"></circle>
        </svg>
      </span>
      <label class="importationText ml-5 progress-label">Importation en cours ...</label>
      <!-- <progress :value="progressValue" max="100" class="progress-bar" ></progress> -->
    </div>

    <div v-if="showMessage" class="alert alert-success d-flex align-items-center"
      style="position: absolute; top: 10px; right: 10px; font-size: 20px; z-index: 2;">
      <i class="fa fa-check-circle fa-2x mr-3 zoom-icon"></i>
      {{ successMessage }}
    </div>

    <div v-if="showError" class="alert alert-danger d-flex align-items-center"
      style="position: absolute; top: 10px; right: 10px; font-size: 20px; z-index: 2;">
      <i class="fa fa-exclamation-circle fa-2x mr-3 zoom-icon"></i>
      {{ errorMessage }}
    </div>

    <!--  -->
    <div class="col-lg-12 grid-margin stretch-card px-2" style="position: relative; z-index: 1; margin-top: 20px;">
      <div class="card">
        <div class="card-body">
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex"
            style="background-color: rgba(4, 251, 132, 0.224); border-radius: 10px;">
            <div class="d-flex justify-content-between align-items-center shadow-success pt-4 pb-3">
              <h6 class="text-black text-capitalize ps-3">
                LISTES DES ADMI
              </h6>
            </div>
          </div>
          <div class="table-responsive pt-3 px-[20px]">
            <table class="table table-hover">
              <thead class="table-dark">
                <tr>
                  <th>Année</th>
                  <th>N° Insc</th>
                  <th>Nom</th>
                  <th>Prénom</th>
                  <th>Serie</th>
                  <th>Secteur</th>
                  <th>Spécialité</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <template v-if="searchResult">
                  <tr :key="searchResult.annee">
                    <td class="align-middle">{{ searchResult.annee }}</td>
                    <td class="align-middle">{{ searchResult.numCandidat }}</td>
                    <td class="align-middle">{{ searchResult.nomCandidat }}</td>
                    <td class="align-middle">{{ searchResult.prenomCandidat }}</td>
                    <td class="align-middle">{{ searchResult.libelleSerie }}</td>
                    <td class="align-middle">{{ searchResult.libelleSecteur }}</td>
                    <td class="align-middle">{{ searchResult.nomSpecialite }}</td>
                    <td class="align-middle">
                      <button @click="demanderAction(searchResult)" class="btn btn-warning" ref="demanderButton">
                        <i class="fas fa-info"></i>
                      </button>
                    </td>
                  </tr>
                </template>
                <template v-else>
                  <tr v-for="item in paginatedData" :key="item.annee">
                    <td class="align-middle">{{ item.annee }}</td>
                    <td class="align-middle">{{ item.numCandidat }}</td>
                    <td class="align-middle">{{ item.nomCandidat }}</td>
                    <td class="align-middle">{{ item.prenomCandidat }}</td>
                    <td class="align-middle">{{ item.libelleSerie }}</td>
                    <td class="align-middle">{{ item.libelleSecteur }}</td>
                    <td class="align-middle">{{ item.nomSpecialite }}</td>
                    <td class="align-middle">
                      <button @click="demanderAction(item)" class="btn btn-warning" ref="demanderButton">
                        <i class="fas fa-info"></i>
                      </button>
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
        <!-- Pagination -->
        <nav>
          <ul class="pagination justify-content-end mr-2" style="margin: ;">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
              <button class="page-link" @click="previousPage">Précédent</button>
            </li>
            <li class="page-item active">
              <span class="page-link">{{ currentPage }}</span>
            </li>
            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
              <button class="page-link" @click="nextPage">Suivant</button>
            </li>
          </ul>
          <!-- Affichage du nombre de page -->
          <div class="pagination justify-content-end mr-[10px]">
            Page {{ currentPage }} de {{ totalPages }}
          </div>
        </nav>
      </div>
    </div>



    <br /><br /><br />

    <!-- Modale -->
    <div class="modal" :class="{ 'is-active': afficherModal }">
      <div class="modal-background"></div>
      <div class="modal-content" v-if="afficherModal">
        <br />
        <div class="custom-modal-header">
          <h2>DEMANDE</h2>
          <div v-if="showError1" class="alert alert-danger d-flex align-items-center"
            style="position: absolute; top: 10px; right: 10px; font-size: 20px">
            <i class="fa fa-exclamation-circle fa-2x mr-3 zoom-icon"></i>
            {{ errorMessage1 }}
          </div>
        </div>
        <br /><br />
        <div class="custom-modal-body mr-7">
          <div class="flex-container">
            <div class="label form-control" style="border: 0">N° Inscr:</div>
            <div class="data form-control" style="border: 0; text-align: left;">
              {{ selectedStudent ? selectedStudent.numCandidat : "" }}
            </div>
            <div class="label form-control" style="border: 0">Mention:</div>
            <div class="data form-control" style="border: 0; text-align: left;">
              {{ selectedStudent ? selectedStudent.mention : "" }}
            </div>
          </div>
          <div class="flex-container mb-4">
            <div class="label form-control" style="border: 0">Nom:</div>
            <div class="data form-control" style="border: 0; text-align: left;">
              {{ selectedStudent ? selectedStudent.nomCandidat : "" }}
            </div>
            <div class="label form-control" style="border: 0">Série:</div>
            <div class="data form-control" style="border: 0; text-align: left;">
              {{ selectedStudent ? selectedStudent.libelleSerie : "" }}
            </div>
          </div>
          <div class="flex-container mb-4">
            <div class="label form-control" style="border: 0">Prénom:</div>
            <div class="data form-control" style="border: 0; text-align: left;">
              {{ selectedStudent ? selectedStudent.prenomCandidat : "" }}
            </div>
            <div class="label form-control" style="border: 0">Centre:</div>
            <div class="data form-control" style="border: 0; text-align: left;">
              <input type="text" v-model="centre" class="form-control" style="border: 0" />
            </div>
          </div>
          <!-- <div class="d-flex mt-2 mr-10 w-50 h-12"> -->
          <div class="flex-container mb-4">
            <div class="label form-control" style="border: 0">Né(e) le:</div>
            <div class="data form-control" style="border: 0; text-align: left;">
              {{ selectedStudent ? selectedStudent.dateNaissance : "" }} à {{ selectedStudent ?
                selectedStudent.lieuNaissance : "" }}
            </div>
            <div class="label form-control" style="border: 0">Année:</div>
            <div class="data form-control" style="border: 0; text-align: left;">
              {{ selectedStudent ? selectedStudent.annee : "" }}
            </div>
          </div>
          <div class="d-flex mt-2 mb-4">
            <div class="label form-control" style="border: 0">N° <span style="color: red">*</span> :</div>
            <div class="data form-control">
              <input type="text" v-model="numeroDemande" id="Numdemande" class="form-control"
                placeholder="Numero de demande" style="border: 0" />
            </div>
            <div class="label form-control" style="border: 0">Date <span style="color: red">*</span> :</div>
            <div class="data form-control">
              <input type="date" v-model="selectedDate" class="form-control" style="border: 0" />
            </div>
          </div>

          <br />
          <br />

          <div class="text-dark">
            <h4>Pièces jointes <span style="color: red;">*</span> :</h4>
            <br />
            <div class="flex-container">
              <label class="form-check form-check-success">
                <input type="checkbox" v-model="pieceJointe" value="acte_de_naissance" class="form-check-input mr-auto" />
                Copie d'acte de naissance ou Photocopie du CIN
              </label>
            </div>
            <div class="flex-container">
              <label>
                <input type="checkbox" v-model="pieceJointe" value="releve_notes" class="form-check-input mr-auto" />
                Relevé des notes ou attestation de réussite pour le baccalauréat
                antérieur à 1999
              </label>
            </div>
            <div class="flex-container">
              <label>
                <input type="checkbox" v-model="pieceJointe" value="versement_bancaire" class="form-check-input" />
                Reçu de versement bancaire au nom de l'intéressé ou Mandat poste
                au nom de l'intéressé
              </label>
            </div>
          </div>
        </div>
        <br />
        <br />

        <div class="custom-modal-footer ml-auto">
          <button class="btn btn-secondary btn-lg mr-5" @click="closeModal">
            Fermer
          </button>
          <button class="btn btn-primary btn-lg" @click="envoyerAction">
            Confirmer
          </button>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation le demande -->
    <div class="modal" :class="{ 'is-active': afficherConfirmation }">
      <div class="modal-background"></div>
      <div class="modal-content">
        <h6 style="text-align: center">Confirme</h6>
        <div class="box">
          <p>Êtes-vous sûr de vouloir confirme ?</p>
          <div class="d-flex justify-content-between">
            <button class="btn" @click="confirmation">OUI</button>
            <button class="btn" @click="annuler">ANNULLE</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from "axios";
// import VueSession from "vue-session"; // Importez la bibliothèque


export default {
  data() {
    return {
      showProgressBar: false,
      progressValue: 0,
      showMessage: false,
      showError: false,
      showError1: false,
      successMessage: "",
      errorMessage: "",
      errorMessage1: "",
      afficheListe: [],
      currentPage: 1,
      perPage: 50,
      searchQuery: "",
      searchYear: "",
      searchResult: null,
      selectedStudent: null,
      afficherModal: false,
      studentHasSubmitted: false,
      selectedDate: null,
      numeroDemande: '',
      centre: "",
      pieceJointe: [],
      afficherConfirmation: false,
      demandeAConfirme: null,
    };
  },

  computed: {

    paginatedData() {
      const startIndex = (this.currentPage - 1) * this.perPage;
      const endIndex = startIndex + this.perPage;
      return this.afficheListe.slice(startIndex, endIndex);
    },
    totalPages() {
      return Math.ceil(this.afficheListe.length / this.perPage);
    },
  },
  methods: {


    async uploadExcel() {
      this.showProgressBar = true; // Afficher la barre de progression (spinner) au début de l'importation

      const fileInput = this.$refs.fileInput;

      if (!fileInput.files || fileInput.files.length === 0) {
        this.showErrorMessage("Veuillez sélectionner un fichier Excel à importer.");
        this.showProgressBar = false; // Cacher la barre de progression
        return;
      }

      // Vérifier si le fichier est au format Excel (xlsx ou xls)
      const allowedFileExtensions = [".xlsx", ".xls"];
      const fileName = fileInput.files[0].name;
      const fileExtension = fileName.slice(((fileName.lastIndexOf(".") - 1) >>> 0) + 2);

      if (!allowedFileExtensions.includes("." + fileExtension.toLowerCase())) {
        this.showErrorMessage("Le fichier sélectionné n'est pas au format Excel (xlsx ou xls).");
        this.showProgressBar = false; // Cacher la barre de progression
        return;
      }

      const formData = new FormData();
      formData.append("excel_file", fileInput.files[0]);

      try {
        const response = await axios.post(
          "http://localhost/applicationjose/src/api/api.php",
          formData,
          {
            onUploadProgress: (progressEvent) => {
              this.progressValue = Math.round((progressEvent.loaded / progressEvent.total) * 100);
            },
          }
        );

        if (response.status === 200) {
          this.showSuccessMessage(response.data.message);
          this.showSuccessMessage("Importation avec succès.");
          this.fetchImportedData();
          this.searchResult = null;
        } else {
          console.error("Erreur lors de l'importation");
        }
      } catch (error) {
        console.error("Erreur lors de la requête HTTP :", error);
      } finally {
        this.showProgressBar = false;
        this.progressValue = 0;
      }
    },


    async fetchImportedData() {
      try {
        const response = await axios.get(
          "http://localhost/applicationjose/src/api/api.php?action=getAll"
        );

        if (response.status === 200) {
          this.afficheListe = response.data;
        } else {
          console.error(
            "Erreur lors de la récupération des données. Réponse du serveur :",
            response.status
          );
        }
      } catch (error) {
        console.error("Erreur lors de la requête HTTP :", error);
      }
    },

    showSuccessMessage(message) {
      this.successMessage = message;
      this.showMessage = true;
      setTimeout(() => {
        this.showMessage = false;
      }, 9000);
    },

    showErrorMessage(message) {
      this.errorMessage = message;
      this.showError = true;
      setTimeout(() => {
        this.showError = false;
      }, 7000);
    },

    showErrorMessage1(message) {
      this.errorMessage1 = message;
      this.showError1 = true;
      setTimeout(() => {
        this.showError1 = false;
      }, 7000);
    },

    previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },

    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },

    searchStudent() {
      const searchQuery = this.searchQuery.trim();
      const searchYear = this.searchYear.trim(); // Ajout de la recherche par année

      if (!/^\d+$/.test(searchQuery)) {
        // || !/^\d{4}$/.test(searchYear)
        this.showError = true;
        this.errorMessage = "Veuillez entrer un numéro d'inscription valide (chiffres uniquement) ET une année";
        this.searchResult = null;
        setTimeout(() => {
          this.showError = false;
        }, 7000);
        return;
      }

      if (searchQuery === "" || searchYear === "") {
        this.showError = true;
        this.errorMessage = "Veuillez entrer à la fois le numéro d'inscription et l'année du baccalauréat.";
        this.searchResult = null;
        setTimeout(() => {
          this.showError = false;
        }, 7000);
        return;
      }

      const foundStudent = this.afficheListe.find((student) => {
        return (
          student.numCandidat.toString() === searchQuery &&
          student.annee.toString() === searchYear
        );
      });

      if (foundStudent) {
        this.searchResult = foundStudent;
        this.showError = false;
      } else {
        this.showError = true;
        this.errorMessage = "Aucun étudiant trouvé avec ce numéro d'inscription et cette année du baccalauréat.";
        this.searchResult = null;
        setTimeout(() => {
          this.showError = false;
        }, 7000);
      }
    },


    closeModal() {
      this.selectedStudent = null;
      this.numeroDemande = null;
      this.afficherModal = false;
    },

    async demanderAction(student) {
      this.selectedStudent = student;
      this.afficherModal = true;
    },

    async envoyerAction() {
      // Vérifiez si le numéro de demande, le centre et l'étudiant sont remplis
      if (this.selectedStudent && this.numeroDemande && this.centre) {
        // Vérifiez si la date est sélectionnée
        if (!this.selectedDate) {
          this.showErrorMessage1("Veuillez sélectionner une date.");
          return;
        }

        // Comptez le nombre de pièces jointes cochées
        const pieceJointeSelectionnees = this.pieceJointe.filter(item => item).length;

        if (pieceJointeSelectionnees < 3) {
          this.showErrorMessage1("Sélectionnez les trois pièces jointes.");
          return;
        }

        try {
          const checkResponse = await axios.get(
            `http://localhost/applicationjose/src/api/insert_demande.php?action=checkDemande&numCandidat=${this.selectedStudent.numCandidat}&annee=${this.selectedStudent.annee}`
          );

          if (checkResponse.status === 200) {
            const hasSubmitted = checkResponse.data.hasSubmitted;

            if (hasSubmitted) {
              this.showErrorMessage1("Cet étudiant a déjà soumis une demande.");
              this.pieceJointe = [];
              return;
            } else {
              const response = await axios.post(
                "http://localhost/applicationjose/src/api/insert_demande.php",
                {
                  action: "submitDemande",
                  numCandidat: this.selectedStudent.numCandidat,
                  numeroDemande: this.numeroDemande,
                  nomCandidat: this.selectedStudent.nomCandidat,
                  prenomCandidat: this.selectedStudent.prenomCandidat,
                  libelleCourt: this.selectedStudent.libelleCourt,
                  mention: this.selectedStudent.mention,
                  centre: this.centre,
                  date: this.selectedDate,
                  annee: this.selectedStudent.annee,
                  pieceJointe: this.pieceJointe,
                }
              );

              if (response.status === 200) {
                if (response.data.success) {
                  this.selectedStudent = null;
                  this.numeroDemande = null;
                  this.showSuccessMessage("La demande a été envoyée avec succès.");
                } else {
                  console.log(response.data);
                  this.showSuccessMessage("La demande a été envoyée avec succès.");
                  this.closeModal();
                  this.pieceJointe = [];
                }
              } else {
                console.log(response);
                this.showErrorMessage1("Erreur lors de l'envoi de la demande.");
              }
            }
          } else {
            this.showErrorMessage1("Erreur lors de la vérification de la demande.");
          }
        } catch (error) {
          console.error("Erreur lors de la requête HTTP :", error);
          this.showErrorMessage1("Erreur lors de l'envoi de la demande.");
        }
      } else {
        this.showErrorMessage1("Veuillez remplir le numéro de demande, sélectionner un étudiant et saisir un centre.");
      }
    },



    ouvrirConfirmation(appartement) {
      this.appartementASupprimer = appartement;
      this.afficherConfirmationSuppression = true;
    },

    async Confirmation() {
      if (this.appartementASupprimer) {
        await this.envoyerRequeteSuppressionAppartement(
          this.appartementASupprimer
        );
        this.appartementASupprimer = null;
        this.afficherConfirmationSuppression = false;
      }
    },

    annuler() {
      this.appartementASupprimer = null;
      this.afficherConfirmationSuppression = false;
    },
  },
  mounted() {
    this.fetchImportedData();
  },
};
</script>

<style scoped>
.card {
  box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.278);
  border-radius: 0.8rem;
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

.flex-container {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
}

.progress-container {
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(0, 0, 0, 0.3);
  /* Arrière-plan semi-transparent */
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 999;
  /* Assurez-vous que la barre de progression est au-dessus de tout le reste */
}



.progress-label {
  color: white;
  font-size: 20px;
  /* Couleur du texte */
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

.progress-bar {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 40px;
}

.indeterminate {
  background-color: #2196F3;
  /* Couleur de la barre de progression */
  height: 4px;
  width: 100%;
  animation: indeterminate 2s linear infinite;
}

@keyframes indeterminate {
  0% {
    left: -35%;
    right: 100%;
  }

  60% {
    left: 100%;
    right: -90%;
  }

  100% {
    left: 100%;
    right: -90%;
  }
}














svg {
  position: relative;
  width: 150px;
  height: 150px;
  animation: rotate 2s linear infinite;
}



@keyframes rotate {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }

}

svg circle {
  width: 100%;
  height: 100%;
  fill: none;
  stroke-width: 10;
  stroke: #00a1ff;
  stroke-linecap: round;
  transform: translate(5px, 5px);
  stroke-dasharray: 440;
  stroke-dashoffset: 440;
  animation: animate 4s linear infinite;
}


@keyframes animate {

  0%,
  100% {
    stroke-dashoffset: 440;
  }

  50% {
    stroke-dashoffset: 0;
  }

  50.1% {
    stroke-dashoffset: 880;
  }
}
</style>
