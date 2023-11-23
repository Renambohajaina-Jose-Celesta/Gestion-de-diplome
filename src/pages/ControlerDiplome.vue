<template>
  <div>
    <div class="header"
      style="background-color:  rgba(99, 122, 224, 0.609); width: 100%; height: 20vh; position: relative; border-bottom-left-radius: 15px;">
      <div class="" style="text-align: left;">
        <h2>CONTROLER</h2>
      </div>
      <!--  -->
      <div class="d-flex" style="margin-left: auto;">
        <input type="text" v-model="banqueQuery"
          class="bg-gray-200 focus:bg-white border border-gray-300 rounded-md py-1 px-3 w-26 mr-1"
          placeholder="Recherche du banque" style="height: 45px;" />
        <input type="text" v-model="dateQuery"
          class="bg-gray-200 focus:bg-white border border-gray-300 rounded-md py-1 px-3 w-26 mr-1"
          placeholder="Date (AAAA-MM-JJ)" style="height: 45px;" />

        <button @click="searchByDate" class="btn btn-primary" style="height: 45px;"><i class="fa fa-search"></i></button>
        <button @click="resetSearch" class="btn btn-secondary ml-2" style="height: 45px;"><i
            class="fa fa-times"></i></button>
      </div>
    </div>

    <!--  -->
    <!-- Messages d'erreur ou de succès -->
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


    <div class="col-lg-12 grid-margin stretch-card mt-20 px-2" style="position: relative; z-index: 1;">
      <div class="card">
        <div class="card-body">
          <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex"
            style="background-color: rgba(4, 251, 132, 0.224); border-radius: 10px;">
            <div class="d-flex justify-content-between align-items-center shadow-success pt-4 pb-3">
              <h6 class="text-black text-capitalize ps-3">
                LISTES DES CONTROLER
              </h6>
            </div>
            <!-- Boutons pour imprimer en PDF -->
            <div class="d-flex ml-auto">
              <!-- PDF -->
              <button @click="generatePDF" class="btn btn-danger d-flex align-items-center mr-3 h-10 mt-3"
                :disabled="!shouldEnablePDFButton">
                <i class="fas fa-file-pdf fa-2x w-7"></i>
              </button>
            </div>
          </div>
          <div class="table-responsive pt-3">
            <table class="table table-hover">
              <thead class="table-dark">
                <tr>
                  <th>N°</th>
                  <th>Nom</th>
                  <th>Prénom</th>
                  <th>Banque</th>
                  <th @click="sortByDate">Date du contrôler</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <template v-if="searchResult && searchResult.length > 0">
                  <tr v-for="item in searchResult" :key="item.id">
                    <td class="align-middle">{{ item.id }}</td>
                    <td class="align-middle">{{ item.nomCandidat }}</td>
                    <td class="align-middle">{{ item.prenomCandidat }}</td>
                    <td class="align-middle">{{ item.banque }}</td>
                    <td class="align-middle">{{ formatDate(item.date) }}</td>
                    <td class="align-middle">
                      <button @click="editItem(item)" class="btn btn-primary"><i class="fas fa-edit"></i></button>
                      <button @click="deleteItem(item)" class="btn btn-danger ml-3"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>
                </template>
                <template v-else>
                  <tr v-for="item in controlerTable" :key="item.id">
                    <td class="align-middle">{{ item.id }}</td>
                    <td class="align-middle">{{ item.nomCandidat }}</td>
                    <td class="align-middle">{{ item.prenomCandidat }}</td>
                    <td class="align-middle">{{ item.banque }}</td>
                    <td class="align-middle">{{ formatDate(item.date) }}</td>
                    <td class="align-middle">
                      <button @click="editItem(item)" class="btn btn-primary"><i class="fas fa-edit"></i></button>
                      <button @click="deleteItem(item)" class="btn btn-danger ml-3"><i class="fas fa-trash"></i></button>
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
          <div class="pagination justify-content-end mr-20">
            Page {{ currentPage }} de {{ totalPages }}
          </div>
        </nav>
      </div>
    </div>

    <!-- Modal de modification -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true"
      v-if="afficherModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Modifier le contrôleur</h5>
          </div>
          <div class="modal-body">
            <!-- Formulaire de modification ici -->
            <!-- Vous pouvez ajouter des champs de formulaire pour la modification des données -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeModal">Fermer</button>
            <button type="button" class="btn btn-primary">Enregistrer les modifications</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
  
<script>
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
pdfMake.vfs = pdfFonts.pdfMake.vfs;

// function formatDate(dateString) {
//   const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
//   const formattedDate = new Date(dateString).toLocaleDateString('fr-FR', options);
//   return formattedDate;
// }

export default {
  data() {
    return {
      afficherModal: false,
      showMessage: false,
      showError: false,
      successMessage: "",
      errorMessage: "",
      currentPage: 1,
      perPage: 50,
      controlerTable: [], // Tableau de données de contrôle
      dateQuery: "", // Champ pour la recherche par date
      banqueQuery: "",
      searchResult: null,
      searchError: "",
      originalControlerTable: [], // Copie des données d'origine
      controlerResults: [],
      shouldEnablePDFButton: false,
    };
  },
  computed: {

    paginatedData() {
      const startIndex = (this.currentPage - 1) * this.perPage;
      const endIndex = startIndex + this.perPage;
      return this.controlerTable.slice(startIndex, endIndex);
    },
    totalPages() {
      return Math.ceil(this.controlerTable.length / this.perPage);
    },
  },
  methods: {
    formatDate(dateString) {
      if (!dateString) return '';
      const options = { day: '2-digit', month: 'short', year: 'numeric' };
      const formattedDate = new Date(dateString).toLocaleDateString('fr-FR', options);
      return formattedDate;
    },

    // Méthode de tri par date
    sortByDate() {
      console.log("sds");
      if (this.searchResult && this.searchResult.length > 0) {
        this.searchResult.sort((a, b) => {
          // Assurez-vous de parser les dates correctement, car elles sont au format AAAA-MM-JJ
          const dateA = new Date(a.date);
          const dateB = new Date(b.date);
          return dateB - dateA; // Tri par date du contrôleur la plus récente en premier
        });
      }
    },

    async chargerDonneesDeControle() {
      try {
        const response = await fetch('http://localhost/applicationjose/src/api/insert_controler.php', {
          method: 'GET',
        });

        if (response.ok) {
          const data = await response.json();
          this.controlerTable = data;
          // Sauvegardez une copie des données d'origine
          this.originalControlerTable = [...data];
          // controlerTable.date = formatDate(controlerTable.date);
        } else {
          console.error('Erreur lors de la récupération des données de contrôle.');
        }
      } catch (error) {
        console.error('Erreur lors de la requête HTTP :', error);
      }
    },

    searchByDate() {
      // this.showErrorMessage(""); // Réinitialisez le message d'erreur

      // Vérifiez si à la fois la date et la banque ont été renseignées
      if (this.dateQuery && this.banqueQuery) {
        this.searchResult = this.controlerTable.filter(
          item => item.date === this.dateQuery && item.banque === this.banqueQuery
        );
        // console.log("Résultats de la recherche : ", this.searchResult);

        // Activez le bouton PDF lorsque des résultats sont trouvés
        this.shouldEnablePDFButton = this.searchResult.length > 0;

        if (this.searchResult.length === 0) {
          this.showErrorMessage("Aucun résultat trouvé pour la date et la banque spécifiées.");
        }
      } else if (this.banqueQuery) {
        // Si seulement la banque est renseignée
        this.searchResult = this.controlerTable.filter(
          item => item.banque === this.banqueQuery
        );

        this.shouldEnablePDFButton = this.searchResult.length > 0;

        if (this.searchResult.length === 0) {
          this.showErrorMessage("Aucun résultat trouvé pour la banque spécifiée.");
        }
      } else {
        this.showErrorMessage("Veuillez entrer à la fois une date valide au format AAAA-MM-JJ et une banque, ou seulement une banque.");
        this.searchResult = [];

        // Désactivez le bouton PDF lorsque la recherche n'est pas effectuée
        this.shouldEnablePDFButton = false;
      }
    },


    generatePDF() {
      console.log("Fonction generatePDF appelée.");
      if (this.searchResult && this.searchResult.length === 0) {
        console.error("Aucun résultat de recherche à inclure dans le PDF.");
        return;
      }

      // Create the content for the PDF
      const content = [];

      // Header
      content.push({ text: 'BACCALAUREAT', style: 'header' });
      content.push({ text: ' ', style: 'emptyline1' }); // Add an empty line
      // Center-aligned line
      content.push({
        text: `${this.banqueQuery} : DEPOT`,
        style: 'centeredtext',
        alignment: 'center', // Center-align the text
      });
      content.push({
        text: `Controler : ${this.dateQuery}`,
        style: 'centeredtext1',
        alignment: 'center', // Center-align the text
      });

      content.push({ text: ' ', style: 'emptyline' }); // Add an empty line
      content.push({ text: ' ', style: 'emptyline' }); // Add another empty line

      // Data from the search results
      let studentCount = 0;

      this.searchResult.forEach((item) => {
        studentCount++;
        content.push(`${studentCount} - ${item.nomCandidat} ${item.prenomCandidat}`);
      });

      content.push({ text: ' ', style: 'emptyline1' }); // Add an empty line
      // Add "Arrête au nombre X" at the end
      content.push(`Arrête au nombre ${studentCount}`);

      // Define styles for the content
      const styles = {
        header: {
          fontSize: 25,
          bold: true,
          alignment: 'center',
        },
        centeredtext: {
          fontSize: 19,
          alignment: 'center', // Center-align the text
        },
        centeredtext1: {
          fontSize: 14,
          alignment: 'center', // Center-align the text
        },
        emptyline: {
          marginTop: 30,
        },
        emptyline1: {
          marginTop: 25,
        },
      };

      // Define the PDF document
      const docDefinition = {
        content,
        styles,
      };

      // Generate the PDF using pdfMake
      const pdfDoc = pdfMake.createPdf(docDefinition);

      // Download the PDF with the specified file name
      pdfDoc.download("rapport_controler.pdf");
    },


    resetSearch() {
      this.searchError = "";
      this.searchResult = null;
      this.dateQuery = ""; // Réinitialisez le champ de recherche
      this.banqueQuery = "";
      this.shouldEnablePDFButton = false;
    },

    showSuccessMessage(message) {
      this.successMessage = message;
      this.showMessage = true;
      setTimeout(() => {
        this.showMessage = false;
      }, 7000);
    },

    showErrorMessage(message) {
      this.errorMessage = message;
      this.showError = true;
      setTimeout(() => {
        this.showError = false;
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

    closeModal() {
      this.afficherModal = false;
      // Réinitialisez les données de modification si nécessaire
    },
    editItem(student) {
      this.selectedStudent = student; // Stockez l'élément que vous souhaitez modifier
      this.afficherModal = true; // Ouvrez le modal de modification
    },
  },
  mounted() {
    this.chargerDonneesDeControle();
  },
};
</script>
<style>
.card {
  box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.278);
  border-radius: 0.8rem;
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
</style>
  