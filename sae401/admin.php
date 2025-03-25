<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Administrateurs</title>
    <script>
        async function fetchAdmins() {
            try {
                const response = await fetch('admin_api.php');
                const data = await response.json();
                
                const tableBody = document.getElementById("adminTableBody");
                tableBody.innerHTML = "";
                
                if (data.admins) {
                    data.admins.forEach(admin => {
                        let row = `<tr>
                            <td>${admin.id_admin}</td>
                            <td>${admin.nom}</td>
                            <td>${admin.prenom}</td>
                            <td>${admin.email}</td>
                            <td><button onclick="deleteAdmin(${admin.id_admin})">Supprimer</button></td>
                        </tr>`;
                        tableBody.innerHTML += row;
                    });
                } else {
                    tableBody.innerHTML = `<tr><td colspan="5">Aucun administrateur trouvé.</td></tr>`;
                }
            } catch (error) {
                console.error("Erreur lors de la récupération :", error);
            }
        }
        
        async function addAdmin() {
            const nom = document.getElementById("nom").value;
            const prenom = document.getElementById("prenom").value;
            const email = document.getElementById("email").value;
            const mot_de_passe = document.getElementById("mot_de_passe").value;
            
            if (!nom || !prenom || !email || !mot_de_passe) {
                alert("Veuillez remplir tous les champs.");
                return;
            }

            try {
                const response = await fetch('admin_api.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nom, prenom, email, mot_de_passe })
                });

                const data = await response.json();
                alert(data.message);
                fetchAdmins();
            } catch (error) {
                console.error("Erreur lors de l'ajout :", error);
            }
        }
        
        async function deleteAdmin(id) {
            if (!confirm("Voulez-vous vraiment supprimer cet administrateur ?")) return;

            try {
                const response = await fetch(`admin_api.php?id_admin=${id}`, { method: 'DELETE' });
                const data = await response.json();
                alert(data.message);
                fetchAdmins();
            } catch (error) {
                console.error("Erreur lors de la suppression :", error);
            }
        }
        
        document.addEventListener("DOMContentLoaded", fetchAdmins);
    </script>
</head>
<body>
    <h2>Gestion des Administrateurs</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="adminTableBody"></tbody>
    </table>
    <h3>Ajouter un Administrateur</h3>
    <input type="text" id="nom" placeholder="Nom" required>
    <input type="text" id="prenom" placeholder="Prénom" required>
    <input type="email" id="email" placeholder="Email" required>
    <input type="password" id="mot_de_passe" placeholder="Mot de passe" required>
    <button onclick="addAdmin()">Ajouter</button>
</body>
</html>
