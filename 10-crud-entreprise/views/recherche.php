<table class="table table-bordered table-striped table-hover text-center tableau">
    <thead class="table-dark">
        <tr>
            <th>
                <?php echo ucfirst($id) ?>
                <?php foreach($fields as $value) : ?>
                    <th><?php echo ucfirst($value["Field"]) ?></th>
                <?php endforeach; ?>
            </th>
            <th>Consulter</th>
            <th>Modifier</th>
            <th>Supprimer</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $dataEmploye) : ?> 
            <tr>
                <td><?php echo implode(("</td><td>"), $dataEmploye) ?></td>
                <td><a href="?op=select&id=<?php echo $dataEmploye[$id] ?>" class="btn btn-info"><i class="fa-sharp fa-solid fa-eye"></i></a></td>
                <td><a href="?op=update&id=<?php echo $dataEmploye[$id] ?>" class="btn btn-warning"><i class="fa-sharp fa-solid fa-user-pen"></i></a></td>
                <td><a href="?op=delete&id=<?php echo $dataEmploye[$id] ?>" class="btn btn-danger" onclick="return(confirm('💬 Vous êtes sur le point de supprimer définitivement cet employé. Voulez-vous continuer ?'))"><i class="fa-sharp fa-solid fa-user-xmark"></i></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>