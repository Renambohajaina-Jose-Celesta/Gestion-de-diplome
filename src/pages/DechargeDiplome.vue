<template>
  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>DECHARGE</h2>
    </div>

    <!--  -->
    <div class="card1">
      <div class="card-body dashboard-tabs p-0 bg-green">
        <ul class="nav nav-tabs px-4 " role="tablist">
          <li class="nav-item" style="border: 2px solid black;">
            <a class="nav-link" id="Decharge-tab" data-bs-toggle="tab" href="#Decharge" role="tab"
              aria-controls="Decharge" aria-selected="false">Decharge de Diplôme</a>
          </li>
          <li class="nav-item" style="border: 2px solid black;">
            <a class="nav-link" id="Duplique-tab" data-bs-toggle="tab" href="#Duplique" role="tab"
              aria-controls="Duplique" aria-selected="false">Diplôme Duplique</a>
          </li>
        </ul>
        <div class="tab-content py-0 px-0">
          <div class="tab-pane fade" id="Decharge" role="tabpanel" aria-labelledby="Decharge-tab">
             <!-- Ajoutez le champ de saisie de date et le bouton de filtrage -->
      <div class="form-group d-flex mt-4">
        <label for="dateDecharge" class="form-label">Filtrer par date de décharge :</label>
        <input type="date" v-model="dateFiltre" class="form-control" id="dateDecharge" style="width: 150px;">
        <button @click="filtrerParDate" class="btn btn-secondary">Filtrer</button>
      </div>
      
            <div class="d-flex flex-wrap justify-content-xl-between">
              <div class="col-lg-12 grid-margin stretch-card mt-20 px-2" style="position: relative; z-index: 1;">
                <div class="card">
                  <div class="card-body">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex"
                      style="background-color: #d2a6797f; border-radius: 10px;">
                      <div class="d-flex justify-content-between align-items-center shadow-success pt-4 pb-3">
                        <h6 class="text-black text-capitalize ps-3">LISTE DES DECHARGE</h6>
                      </div>
                      <!-- Boutons pour imprimer en PDF et Excel -->
                      <div class="d-flex ml-auto">
                        <button @click="imprimerPDF" class="btn btn-outline-danger d-flex align-items-center h-10 mt-3">
                          <i class="fas fa-file-pdf fa-2x w-7"></i>
                        </button>
                      </div>
                    </div>
                    <div class="table-responsive pt-3">
                      <table class="table table-hover">
                        <thead class="table-dark">
                          <tr>
                            <th>N° Demande</th>
                            <th>N° Ordre</th>
                            <th>N° Matrice</th>
                            <th>N° Candidat</th>
                            <th>Nom Prénom</th>
                            <th>Année</th>
                            <th>Centre</th>
                            <th>Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="item in dechargeData" :key="item.numerodemande">
                            <td>{{ item.numerodemande }}</td>
                            <td>{{ item.numeroordre }}</td>
                            <td>{{ item.numeromatrice }}</td>
                            <td>{{ item.numCandidat }}</td>
                            <td>{{ item.nomCandidat }}{{ item.prenomCandidat }}</td>
                            <td>{{ item.annee }}</td>
                            <td>{{ item.centre }}</td>
                            <td>{{ item.dateDecharge }}</td>
                          </tr>
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
            </div>
          </div>

          <div class="tab-pane fade" id="Duplique" role="tabpanel" aria-labelledby="Duplique-tab">
            <div class="d-flex flex-wrap justify-content-xl-between">
              <div class="col-lg-12 grid-margin stretch-card mt-20 px-2" style="position: relative; z-index: 1;">
                <div class="card">
                  <div class="card-body">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex"
                      style="background-color: #d2a6797f; border-radius: 10px;">
                      <div class="d-flex justify-content-between align-items-center shadow-success pt-4 pb-3">
                        <h6 class="text-black text-capitalize ps-3">LISTES DES DIPLOME DUPLIQUE</h6>
                      </div>
                      <!-- Boutons pour imprimer en PDF et Excel -->
                      <div class="d-flex ml-auto">
                        <button @click="imprimerPDF" class="btn btn-outline-danger d-flex align-items-center h-10 mt-3">
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
                            <th>N° Insc</th>
                            <th>Série</th>
                            <th>Centre</th>
                            <th>Année</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <template v-if="searchResult">
                            <!-- Utiliser une boucle pour afficher tous les résultats -->
                            <template v-for="(result, index) in searchResult" :key="index">
                              <tr>
                                <td class="align-middle">{{ result.id }}</td>
                                <td class="align-middle">{{ result.nomCandidat }}</td>
                                <td class="align-middle">{{ result.prenomCandidat }}</td>
                                <td class="align-middle">{{ result.numCandidat }}</td>
                                <td class="align-middle">{{ result.libelleCourt }}</td>
                                <td class="align-middle">{{ result.centre }}</td>
                                <td class="align-middle">{{ result.annee }}</td>
                                <td class="align-middle"
                                  :class="result.statut === 'Validé' ? 'bg-label-primary me-1 badge' : (result.statut === 'En attente' ? 'bg-label-waiting me-1 badge-waiting' : '')">
                                  {{ result.statut }}
                                </td>
                                <td class="align-middle">
                                  <button @click="modifierDemande(result)" class="btn btn-primary ml-1">
                                    <i class="fas fa-edit"></i>
                                  </button>
                                  <button @click="controlerAction(result)" class="btn btn-primary ml-1">
                                    <i class="fas fa-check"></i>
                                  </button>
                                </td>
                              </tr>
                            </template>
                          </template>
                          <template v-else>
                            <tr v-for="demande in paginatedData" :key="demande.id">
                              <td class="align-middle">{{ demande.id }}</td>
                              <td class="align-middle">{{ demande.nomCandidat }}</td>
                              <td class="align-middle">{{ demande.prenomCandidat }}</td>
                              <td class="align-middle">{{ demande.numCandidat }}</td>
                              <td class="align-middle">{{ demande.libelleCourt }}</td>
                              <td class="align-middle">{{ demande.centre }}</td>
                              <td class="align-middle">{{ demande.annee }}</td>
                              <td class="align-middle">{{ demande.date }}</td>
                              <td class="align-middle"
                                :class="demande.statut === 'Validé' ? 'bg-label-primary me-1 badge' : (demande.statut === 'En attente' ? 'bg-label-waiting me-1 badge-waiting' : '')">
                                {{ demande.statut }}
                              </td>
                              <td class="align-middle">
                                <button @click="modifierDemande(demande)" class="btn btn-primary ml-1">
                                  <i class="fas fa-edit"></i>
                                </button>
                                <button @click="controlerAction(demande)" class="btn btn-primary ml-1">
                                  <i class="fas fa-check"></i>
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
                    <div class="pagination justify-content-end mr-20">
                      Page {{ currentPage }} de {{ totalPages }}
                    </div>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--  -->

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
      dechargeData: [], // Pour stocker les données de la table decharge
      dateFiltre: null,
    };
  },
  mounted() {
    this.chargerDecharge();
  },
  methods: {
    async chargerDecharge() {
      try {
        const response = await axios.get("http://localhost/applicationjose/src/api/insert_decharge.php");

        if (response.status === 200) {
          this.dechargeData = response.data;
        } else {
          console.error("Erreur lors de la récupération des données de la table decharge. Réponse du serveur :", response.status);
        }
      } catch (error) {
        console.error("Erreur lors de la requête HTTP :", error);
      }
    },

    imprimerPDF() {
      try {
        // Initialisez le document PDF
        const documentDefinition = {
          content: [
            { text: 'Liste de Décharge', style: 'header' },
            this.getTableData(), // Utilisez la fonction pour récupérer les données du tableau
          ],
          pageMargins: [10, 10, 10, 10], // [left, top, right, bottom]
          styles: {
            header: {
              fontSize: 18,
              bold: true,
              margin: [0, 0, 0, 10],
            },
          },
        };

        // Générez le PDF
        pdfMake.createPdf(documentDefinition).download('liste_decharge.pdf');

      } catch (error) {
        console.error("Erreur lors de la génération du PDF :", error);
      }
    },

    getTableData() {
      // Créez une matrice de données pour le tableau PDF
      return {
        table: {
          headerRows: 1,
          widths: ['auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto'],
          body: [
            [
              'N° Demande', 'N° Ordre', 'N° Matrice', 'N° Candidat',
              'Nom', 'Prénom', 'Année', 'Centre', 'Date de Décharge'
            ],
            ...this.dechargeData.map(item => [
              item.numerodemande,
              item.numeroordre,
              item.numeromatrice,
              item.numCandidat,
              item.nomCandidat,
              item.prenomCandidat,
              item.annee,
              item.centre,
              item.dateDecharge
            ]),
          ],
        },
      };
    },

    filtrerParDate() {
      if (this.dateFiltre) {
        // Si une date est sélectionnée, filtrez les données par cette date
        this.dechargeData = this.dechargeData.filter(item => item.dateDecharge === this.dateFiltre);
      } else {
        // Si aucune date n'est sélectionnée, affichez toutes les données
        this.chargerDecharge();
      }
    },
  },
};
</script>
<style scoped>
.nav-tabs .nav-item .nav-link.active {
  border: 0;
  border-bottom: 3px solid #5646ff;
  color: #4d83ff;
}

.nav-tabs .nav-item .nav-link {
  border: 0;
  background: rgb(255, 255, 255);
}

.nav-tabs .nav-link.active,
.nav-tabs .nav-item.show .nav-link {
  color: #000;
  background-color: #09bd3381;
  border-color: #ebedf2 #ebedf2 #ffffff;
}

/* @media (max-width: 767px) .nav-tabs .nav-link {
  padding: 1rem .5rem .2rem .5rem;
} */

.nav-tabs .nav-link {
  background: #f6f8fa;
  color: #000000;
  border-radius: 0;
  border: 1px solid #f3f3f3;
  padding: 1rem 1.5rem .2rem 1.5rem;
}

.nav-tabs .nav-link {
  margin-bottom: -1px;
  background: none;
  border: 1px solid transparent;
  border-top-left-radius: 0.25rem;
  border-top-right-radius: 0.25rem;
}

.nav-link {
  display: block;
  padding: 0.5rem 1rem;
  color: #e1fd0d;
  text-decoration: none;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
}
</style>
