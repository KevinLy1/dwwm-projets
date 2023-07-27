<?php
    $date = new DateTime($data['date_embauche']);
?>

<div class="container text-center mt-5">
    <div class="card" style="width: 20rem; margin: 0 auto;">
    <?php if($data["sexe"] === "m"): ?>
        <img src='https://picsum.photos/id/1005/200/150' alt='Photo du salarié' class='card-img-top'>
    <?php else: ?>
        <img src='https://picsum.photos/id/1011/200/150' alt='Photo de la salariée' class='card-img-top'>
    <?php endif; ?>
        <div class="card-body">
            <h5 class="card-title"><?php echo $data["prenom"] . " " . $data["nom"] ?></h5>
            <table class="table table-bordered my-3">
                <tbody>
                    <tr>
                        <th scope="row">⭐ ID</th>
                        <td><?php echo $data["id_employes"] ?></td>
                    </tr>
                    <tr>
                        <th scope="row">🧑‍🤝‍🧑 Sexe</th>
                        <td><?php echo ucfirst($data["sexe"]) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">🏠 Service</th>
                        <td><?php echo ucfirst($data["service"]) ?></td>
                    </tr>
                    <tr>
                        <th scope="row">🗓️ Date d'embauche</th>
                        <td><?php echo $date->format("d-m-Y") ?></td>
                    </tr>
                    <tr>
                        <th scope="row">💲 Salaire</th>
                        <td><?php echo $data["salaire"] . " €" ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="container">
                <a href="?op=update&id=<?php echo $data[$id] ?>" class="btn btn-warning"><i class="fa-sharp fa-solid fa-user-pen"></i></a>
                <a href="?op=delete&id=<?php echo $data[$id] ?>" class="btn btn-danger" onclick="return(confirm('💬 Vous êtes sur le point de supprimer définitivement cet employé. Voulez-vous continuer ?'))"><i class="fa-sharp fa-solid fa-user-xmark"></i></a>
            </div>
        </div>
    </div>
    <div class="container text-center">
        <a href="?op=list" class="btn btn-primary mt-5"><i class="fa-solid fa-right-to-bracket"></i>&nbsp; Retourner à la gestion des employés</a>
    </div>
</div>