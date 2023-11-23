<template>
  <div>
    <div class="header bg-primary text-white text-left p-3" style="background-color:  rgba(99, 122, 224, 0.609); width: 100%; height: 20vh; position: ;">
      <div class="text-black" style="text-align: left;">
        <h2>DEMANDES</h2>
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
            style="background-color: #d2a6797f; border-radius: 10px;">
            <div class="d-flex justify-content-between align-items-center shadow-success pt-4 pb-3">
              <h6 class="text-black text-capitalize ps-3">LISTES DES DEMANDES</h6>
            </div>
            <!-- Boutons pour imprimer en PDF et Excel -->
            <div class="d-flex ml-auto">
              <button @click="imprimerPDF" class="btn btn-outline-danger d-flex align-items-center h-10 mt-3">
                <i class="fas fa-file-pdf fa-2x w-7"></i>
              </button>
              <button @click="imprimerExcel"
                class="btn btn-outline-success mr-3 ml-5 d-flex align-items-center h-10 mt-3">
                <i class="fas fa-file-excel fa-2x"></i>
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
                  <th>Date</th>
                  <th>Statut</th>
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
                      <td class="align-middle">{{ result.date }}</td>
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

    <!-- Modal pour le contrôle du paiement -->
    <div class="modal" :class="{ 'is-active': afficherModal }">
      <div class="modal-background"></div>
      <div class="modal-content">
        <!-- Contenu de la modal -->
        <div class="custom-modal-header">
          <h5>Contrôle du Paiement</h5>
          <!--  -->
          <div v-if="showError1" class="alert alert-danger d-flex align-items-center"
            style="position: absolute; top: 10px; right: 10px; font-size: 20px">
            <i class="fa fa-exclamation-circle fa-2x mr-3 zoom-icon"></i>
            {{ errorMessage1 }}
          </div>
        </div>
        <br /><br />

        <div class="custom-modal-body mr-7">
          <div class="flex-container">
            <div class="label form-control" style="border: 0">N° Inscr :</div>
            <div class="data form-control">
              {{ selectedDemande ? selectedDemande.numCandidat : '' }}
            </div>
            <div class="label form-control" style="border: 0">Série :</div>
            <div class="data form-control">
              {{ selectedDemande ? selectedDemande.libelleCourt : '' }}
            </div>
          </div>
          <div class="flex-container">
            <div class="label form-control" style="border: 0">Nom :</div>
            <div class="data form-control">
              {{ selectedDemande ? selectedDemande.nomCandidat : '' }}
            </div>
            <div class="label form-control" style="border: 0">Prénom :</div>
            <div class="data form-control">
              {{ selectedDemande ? selectedDemande.prenomCandidat : '' }}
            </div>
          </div>
          <div class="flex-container">
            <div class="label form-control" style="border: 0">Banque <span style="color: red">*</span> :</div>
            <div class="data form-control">
              <input v-model="selectedBanque" id="banque" class="form-control" placeholder="Saisissez la banque"
                style="border: 0" />
            </div>
            <div class="label form-control" style="border: 0">Date <span style="color: red">*</span> :</div>
            <div class="data form-control">
              <input type="date" v-model="selectedDate" class="form-control" style="border: 0" />
            </div>
          </div>
        </div>
        <br /><br />
        <div class="custom-modal-footer ml-auto">
          <button class="btn btn-secondary btn-lg mr-5" @click="closeModal">Fermer</button>
          <button @click="validerDonnees(selectedDemande)" class="btn btn-primary btn-lg">
            Valider
          </button>
        </div>
      </div>
    </div>
    <!--  -->


    <!-- Modal pour la modification de la demande -->
    <div class="modal" :class="{ 'is-active': afficherModalModification }">
      <div class="modal-background"></div>
      <div class="modal-content">
        <div v-if="showError1" class="alert alert-danger d-flex align-items-center"
          style="position: absolute; top: 10px; right: 10px; font-size: 20px">
          <i class="fa fa-exclamation-circle fa-2x mr-3 zoom-icon"></i>
          {{ errorMessage1 }}
        </div>
        <!-- Contenu du formulaire de modification -->
        <h5>Modifier la Demande de Diplôme</h5>
        <form @submit.prevent="enregistrerModification">
          <!-- Utilisez @submit.prevent pour empêcher le rechargement de la page -->
          <!-- Champs de formulaire pour les données de la demande -->
          <div class="form-group">
            <label for="centre">Centre :</label>
            <input type="text" id="centre" v-model="nouveauCentre" class="form-control">
          </div>
          <div class="form-group">
            <label for="date">Date :</label>
            <input type="date" id="date" v-model="nouvelleDate" class="form-control">
            <!-- Utilisez type="date" pour un champ de date -->
          </div>

          <button type="submit" class="btn btn-primary btn-lg">Enregistrer</button>
          <button @click="annulerModification" class="btn btn-secondary btn-lg">Annuler</button>
        </form>
      </div>
    </div>




    <!--  -->
  </div>
</template>

<script>
import * as XLSX from "xlsx";
import axios from "axios";
import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
pdfMake.vfs = pdfFonts.pdfMake.vfs;

function formatDate(dateString) {
  const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
  const formattedDate = new Date(dateString).toLocaleDateString('fr-FR', options);
  return formattedDate;
}

export default {
  data() {
    return {
      showMessage: false,
      showError: false,
      successMessage: "",
      errorMessage1: "",
      showError1: false,
      errorMessage: "",
      demandes: [],
      currentPage: 1,
      perPage: 50,
      registrationQuery: "",
      nameQuery: "",
      activeInput: null,
      searchResult: null,
      demandeCourante: null,
      afficherModal: false,
      selectedDemande: null,
      selectedBanque: "",
      selectedDate: null,
      afficherModalModification: false,
      demandeAModifier: {
        centre: '',
        date: '',
      },
      nouveauCentre: '',
      nouvelleDate: null,
    };
  },
  computed: {
    paginatedData() {
      const startIndex = (this.currentPage - 1) * this.perPage;
      const endIndex = startIndex + this.perPage;
      return this.demandes.slice(startIndex, endIndex);
    },
    totalPages() {
      return Math.ceil(this.demandes.length / this.perPage);
    },
  },
  methods: {
    formatDate,

    modifierDemande(demande) {
      if (demande && demande.centre) {
        this.demandeAModifier = { ...demande };
        this.nouveauCentre = demande.centre;
        this.nouvelleDate = demande.date;
        this.afficherModalModification = true;
      } else {
        console.error('La demande sélectionnée ou son centre est null.');
      }
    },

    enregistrerModification() {
  const dataToUpdate = {
    id: this.demandeAModifier.id,
    centre: this.nouveauCentre,
    date: this.nouvelleDate,
  };

  if (!this.nouvelleDate) {
    this.showErrorMessage1("Veuillez sélectionner une date.");
    return;
  }

  if (!this.nouveauCentre) {
    this.showErrorMessage1("Veuillez saisir un centre.");
    return;
  }

  fetch('http://localhost/applicationjose/src/api/insert_demande.php', {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(dataToUpdate)
  })
    .then(response => response.json())
    .then(data => {
      if (data.message) {
        // Récupérer la date modifiée
        const dateModifiee = dataToUpdate.date;

        // Mise à jour de la demande dans le tableau
        this.demandes = this.demandes.map(d => (d.id === dataToUpdate.id ? { ...d, centre: dataToUpdate.centre, date: dateModifiee } : d));
      }
      this.afficherModalModification = false;
    })
    .catch(error => {
      console.error('Erreur lors de la mise à jour de la demande :', error);
    });
},


    annulerModification() {
      this.afficherModalModification = false;
    },

    async chargerDemandes() {
      try {
        const response = await fetch(
          "http://localhost/applicationjose/src/api/insert_demande.php",
          {
            method: "GET",
          }
        );
        if (response.ok) {
          this.demandes = (await response.json()).map((demande) => {
            if (demande.Statut === "Validé") {
              demande.statut = "Validé";
            } else {
              demande.statut = "En attente";
            }
            demande.date = formatDate(demande.date);
            return demande;
          });
        } else {
          console.error("Erreur lors du chargement des demandes de diplômes.");
        }
      } catch (error) {
        console.error("Erreur lors de la requête HTTP :", error);
      }
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

    showErrorMessage1(message) {
      this.errorMessage1 = message;
      this.showError1 = true;
      setTimeout(() => {
        this.showError1 = false;
      }, 7000);
    },

    search() {
      const searchQuery = this.activeInput === "registration" ? this.registrationQuery : this.nameQuery;

      if (!Array.isArray(this.demandes) || this.demandes.length === 0) {
        this.showError = true;
        this.errorMessage = "Aucune demande de diplôme à imprimer.";
        setTimeout(() => {
          this.showError = false;
        }, 7000);
        return;
      }

      if (searchQuery === "") {
        this.showError = true;
        this.errorMessage = "Le champ de recherche ne peut pas être vide.";
        this.searchResult = null;
        setTimeout(() => {
          this.showError = false;
        }, 7000);
        return;
      }

      if (this.activeInput === "registration" && !/^\d+$/.test(searchQuery)) {
        this.showError = true;
        this.errorMessage = "Veuillez entrer un numéro d'inscription valide (chiffres uniquement).";
        this.searchResult = null;
        setTimeout(() => {
          this.showError = false;
        }, 7000);
        return;
      }

      if (searchQuery.trim() === "") {
        this.showError = true;
        this.errorMessage = "Le champ de recherche ne peut pas être vide.";
        this.searchResult = null;
        setTimeout(() => {
          this.showError = false;
        }, 7000);
        return;
      }

      if (this.activeInput === "name" && !/^[a-zA-Z\séèàâôç]+$/.test(searchQuery)) {
        this.showError = true;
        this.errorMessage = "Caractères spéciaux non autorisés dans la recherche par nom ou prénom.";
        this.searchResult = null;
        setTimeout(() => {
          this.showError = false;
        }, 7000);
        return;
      }

      if (this.activeInput === "registration") {
        const foundStudents = this.demandes.filter(
          (student) => student.numCandidat.toString() === searchQuery
        );

        if (foundStudents.length > 0) {
          this.searchResult = foundStudents;
          this.showError = false;
        } else {
          this.showError = true;
          this.errorMessage = "Aucun étudiant trouvé avec ce numéro d'inscription.";
          setTimeout(() => {
            this.showError = false;
          }, 7000);
        }
      } else {
        if (this.demandes && this.demandes.length > 0) {
          const foundStudents = this.demandes.filter((student) => {
            const fullName = `${student.nomCandidat} ${student.prenomCandidat}`.toLowerCase();
            return fullName.includes(searchQuery.toLowerCase());
          });

          if (foundStudents.length > 0) {
            this.searchResult = foundStudents;
          } else {
            this.showError = true;
            this.errorMessage = "Aucun étudiant trouvé avec ce nom ou prénom.";
            setTimeout(() => {
              this.showError = false;
            }, 7000);
          }
        } else {
          this.showError = true;
          this.errorMessage = "Aucune demande de diplôme disponible.";
          this.searchResult = null;
          setTimeout(() => {
            this.showError = false;
          }, 7000);
        }
      }
    },


    async validerDonnees() {
      console.log("Début de la fonction validerDonnees");
      try {
        // Vérifiez que toutes les données requises sont disponibles
        if (!this.selectedDemande || !this.selectedBanque || !this.selectedDate) {
          this.showErrorMessage1("Veuillez remplir tous les champs.");
          return;
        }

        const nomCandidat = this.selectedDemande.nomCandidat;
        const prenomCandidat = this.selectedDemande.prenomCandidat;

        // Vérifiez si l'étudiant a déjà été contrôlé
        const checkControlResponse = await axios.post("http://localhost/applicationjose/src/api/check_control.php", {
          nomCandidat,
          prenomCandidat,
        });

        if (checkControlResponse.status === 200) {
          const checkControlData = checkControlResponse.data;
          if (checkControlData.error) {
            this.showErrorMessage1(checkControlData.error);
            console.log("Erreur : ", checkControlData.error);
            return;
          }
          if (checkControlData.alreadyControlled) {
            this.showErrorMessage1("Cet étudiant a déjà été contrôlé.");
            return;
          }
        } else {
          console.error("Erreur lors de la vérification du contrôle.");
          this.showErrorMessage1("Une erreur s'est produite lors de la vérification du contrôle.");
          console.log("Réponse du serveur avec un statut différent de 200:", checkControlResponse.status);
          return;
        }

        // Mettez à jour le statut de la demande
        const statutUpdateResponse = await axios.post("http://localhost/applicationjose/src/api/update_statut.php", {
          id: this.selectedDemande.id,
          statut: "Validé",
        });

        if (statutUpdateResponse.status === 200) {
          const statutUpdateData = statutUpdateResponse.data;
          if (statutUpdateData.message) {
            this.showSuccessMessage(statutUpdateData.message);
            console.log("Statut mis à jour avec succès.");

            // Mettez à jour le statut dans l'interface utilisateur
            this.selectedDemande.statut = "Validé";

            // Ensuite, envoyez les données de contrôle
            const controlerResponse = await axios.post("http://localhost/applicationjose/src/api/insert_controler.php", {
              nomCandidat,
              prenomCandidat,
              banque: this.selectedBanque,
              date: this.selectedDate,
            });

            if (controlerResponse.status === 200) {
              const controlerData = controlerResponse.data;
              if (controlerData.message) {
                this.showSuccessMessage(controlerData.message);
                console.log("Données de contrôle envoyées avec succès.");

                // Insérez également dans la table verifier
                const verifierResponse = await axios.post("http://localhost/applicationjose/src/api/insert_verifier.php", {
                  nomCandidat,
                  prenomCandidat,
                  numDemande: this.selectedDemande.numdemande,
                });
                console.log("Données à envoyer dans la table verifier :", nomCandidat, prenomCandidat, this.selectedDemande.numdemande);

                if (verifierResponse.status === 200) {
                  const verifierData = verifierResponse.data;
                  if (verifierData.message) {
                    this.showSuccessMessage(verifierData.message);
                    console.log("Données insérées dans la table verifier avec succès.");
                  } else if (verifierData.error) {
                    this.showErrorMessage1(verifierData.error);
                    console.log("Erreur lors de l'insertion dans la table verifier:", verifierData.error);
                  }
                } else {
                  console.error("Erreur lors de l'insertion dans la table verifier.");
                  this.showErrorMessage1("Une erreur s'est produite lors de l'insertion dans la table verifier.");
                  console.log("Réponse du serveur avec un statut différent de 200:", verifierResponse.status);
                }
              } else if (controlerData.error) {
                this.showErrorMessage1(controlerData.error);
                console.log("Erreur lors de l'envoi des données de contrôle:", controlerData.error);
              }
            } else {
              console.error("Erreur lors de l'envoi des données de contrôle.");
              this.showErrorMessage1("Une erreur s'est produite lors de l'envoi des données de contrôle.");
              console.log("Réponse du serveur avec un statut différent de 200:", controlerResponse.status);
            }
          } else if (statutUpdateData.error) {
            this.showErrorMessage1(statutUpdateData.error);
            console.log("Erreur lors de la mise à jour du statut :", statutUpdateData.error);
          }
        } else {
          console.error("Erreur lors de la mise à jour du statut.");
          this.showErrorMessage1("Une erreur s'est produite lors de la mise à jour du statut.");
          console.log("Réponse du serveur avec un statut différent de 200:", statutUpdateResponse.status);
        }

        // Fermez la modal
        this.closeModal();
      } catch (error) {
        console.error("Erreur lors de la requête HTTP :", error);
        this.showErrorMessage1("Une erreur s'est produite lors de la validation des données.");
      }
    },



    controlerAction(demande) {
      this.selectedDemande = demande;
      this.afficherModal = true;
    },

    closeModal() {
      this.selectedDate = null;
      this.selectedBanque = null;
      this.selectedDemande = null;
      this.afficherModal = false;
    },

    confirmerPaiement() {
      // Mettez en œuvre ici la logique pour confirmer le paiement
      // Une fois le paiement confirmé, vous pouvez fermer le modal
      this.closeModal();
    },

    imprimerPDF() {
      if (!Array.isArray(this.demandes) || this.demandes.length === 0) {
        this.showErrorMessage("Aucune demande de diplôme à imprimer en PDF.");
        return;
      }
      // Calculer le total de demandes
      const totalDemandes = this.demandes.length;
      // Définissez la structure du document PDF
      const docDefinition = {
        content: [
          { text: "Liste des Demandes de Diplômes", style: "header" },
          { text: "\n" },
          {
            table: {
              headerRows: 1,
              widths: ["auto", "auto", "auto", "auto", "auto", "auto", "auto", "auto"],
              body: [
                [
                  { text: "N°", style: "tableHeader" },
                  { text: "Nom", style: "tableHeader" },
                  { text: "Prénom", style: "tableHeader" },
                  { text: "N° Insc", style: "tableHeader" },
                  { text: "Série", style: "tableHeader" },
                  { text: "Centre", style: "tableHeader" },
                  { text: "Année Bac", style: "tableHeader" },
                  { text: "Date", style: "tableHeader" },
                ],
                ...this.demandes.map((demande) => [
                  demande.id,
                  demande.nomCandidat,
                  demande.prenomCandidat,
                  demande.numCandidat,
                  demande.libelleCourt,
                  demande.centre,
                  demande.annee,
                  demande.date,
                ]),
              ],
            },
            style: "taille",
          },
          { text: "\n" }, // Espace avant le total
          { text: `Total de demandes: ${totalDemandes}`, style: "total" },
        ],
        styles: {
          header: {
            fontSize: 18,
            bold: true,
            marginLeft: 150,
          },
          tableHeader: {
            bold: true,
            fillColor: "#FFFF00", // Jaune
          },
          taille: {
            fontSize: 10,
          },
        },
        pageMargins: [12.7, 12.7, 12.7, 12.7], // [Haut, Droite, Bas, Gauche]
      };

      // Générez le PDF
      const pdfDoc = pdfMake.createPdf(docDefinition);
      pdfDoc.download("Liste_demandes_diplomes.pdf"); // Téléchargez le PDF
    },

    imprimerExcel() {
      if (!Array.isArray(this.demandes) || this.demandes.length === 0) {
        this.showErrorMessage("Aucune demande de diplôme à imprimer en EXCEL.");
        return;
      }
      // Créez une structure de données pour les feuilles de calcul
      const worksheet = XLSX.utils.json_to_sheet(this.demandes);
      const workbook = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(workbook, worksheet, "Demandes de Diplômes");

      // Générez le fichier XLSX
      const excelBuffer = XLSX.write(workbook, {
        bookType: "xlsx",
        type: "array",
      });

      // Convertissez le tableau de données en un blob
      const blob = new Blob([excelBuffer], {
        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
      });

      // Créez un objet URL pour le Blob
      const blobURL = URL.createObjectURL(blob);

      // Créez un lien HTML pour télécharger le fichier Excel
      const a = document.createElement("a");
      a.href = blobURL;
      a.download = "Liste_demandes_diplomes.xlsx";
      a.style.display = "none";

      // Ajoutez le lien au document et cliquez dessus pour déclencher le téléchargement
      document.body.appendChild(a);
      a.click();

      // Supprimez le lien du document
      document.body.removeChild(a);
    },
  },
  created() {
    this.chargerDemandes();
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
