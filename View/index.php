<?php 
include ("../Model/conexao.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meu Sistema</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <script src="../script.js" defer></script>
  <style>
    .card-favorito {
      color: gold;
    }

    .btn-favorito-selected {
      color: gold;
    }
  </style>
</head>

<body>

  <div class="container">
    <h1>Meu Sistema</h1>
    <div class="row">
      <div class="col-sm-6">
        <button class="btn btn-primary" id="ver-cards">Ver Cards</button>
      </div>
      <div class="col-sm-6">
        <button class="btn btn-primary" id="favoritos">Favoritos</button>
      </div>
    </div>

    <div id="cards-container" class="d-none">
      <h2>Cards</h2>
      <div class="row" id="card-list">
        <?php foreach ($cards as $card): ?>
          <div class="col-sm-4 card-item" data-card-id="<?php echo $card['id']; ?>">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">
                  <?php echo $card['name']; ?>
                </h5>
                <p class="card-text">Conteúdo do
                  <?php echo $card['tags']; ?>.
                </p>
                <button class="btn btn-link btn-favorito"><i class="fas fa-star"></i> Salvar nos Favoritos</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div id="favoritos-container" class="d-none">
      <h2>Favoritos</h2>
      <div class="row" id="favoritos-list"></div>
    </div>
  </div>
 
</body>

</html>