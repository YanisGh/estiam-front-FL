<template>
  <div class="liste-utilisateurs">
    <h1>Liste des utilisateurs</h1>
    <ul>
      <li v-for="utilisateur in utilisateurs" :key="utilisateur.id">
        {{ utilisateur.username }}
        <button @click="supprimerUtilisateur(utilisateur.id)">Supprimer</button>
      </li>
    </ul>
  </div>
</template>
<script>
export default {
  data() {
    return {
      utilisateurs: []
    };
  },
  mounted() {
    this.fetchUtilisateurs();
  },
  methods: {
    fetchUtilisateurs() {
      fetch('/api/utilisateurs')
        .then(response => response.json())
        .then(data => {
          this.utilisateurs = data;
        })
        .catch(error => {
          console.error('Une erreur s\'est produite lors de la récupération des utilisateurs:', error);
        });
    },
    supprimerUtilisateur(id) {
      if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')) {
        fetch(`/api/utilisateurs/delete/${id}`, {
          method: 'DELETE'
        })
        .then(response => {
          if (response.ok) {
            // Actualiser la liste des utilisateurs après la suppression
            this.fetchUtilisateurs();
          } else {
            console.error('La suppression de l\'utilisateur a échoué');
          }
        })
        .catch(error => {
          console.error('Une erreur s\'est produite lors de la suppression de l\'utilisateur:', error);
        });
      }
    }
  }
};
</script>

<style>
.liste-utilisateurs {
  font-family: Arial, sans-serif;
  margin: 20px;
}

.liste-utilisateurs h1 {
  font-size: 24px;
  color: #333;
}

.liste-utilisateurs ul {
  list-style-type: none;
  padding: 0;
}

.liste-utilisateurs li {
  margin-bottom: 10px;
  padding: 5px;
  background-color: #f4f4f4;
  border-radius: 5px;
}

.liste-utilisateurs li:hover {
  background-color: #e0e0e0;
}
</style>