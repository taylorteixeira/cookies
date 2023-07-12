<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meu Sistema</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    .card-favorito {
      color: gold;
    }

    .btn-favorito-selected {
      color: gold;
      border: none;
      
    }
    .btn-favorito-selected:hover {
      outline: none;
    }
    
    .card {
      height: 280px;
      background-color: #fff;
      box-shadow: 0px 0px 20px 1px rgba(0, 0, 0, 0.20);
      border-radius: 15px;
      text-align: center;
      display: inline-block;
      width: 330px;
      margin-left: 20px;
      margin-top: 20px;
    }

    .btn.focus, .btn:focus {
    outline: 0;
    box-shadow: none;
}



  </style>
</head>

<body>
  <?php
  define('HOST', 'localhost');
  define('USUARIO', 'root');
  define('SENHA', '');
  define('DB', 'criciuma_servicos');

  $conexao = mysqli_connect(HOST, USUARIO, SENHA, DB) or die('Não foi possível conectar');

  $query = "SELECT id, name, tags FROM services";
  $resultado = mysqli_query($conexao, $query);

  $cards = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
  ?>
  <div class="container">
    <h1>Meu Sistema</h1>
    <div class="row">
      <div class="col-sm-6">
        <button class="btn btn-primary" id="ver-cards">Ver Cards</button>
      </div>
      <div class="col-sm-6">
        <button class="btn btn-primary" id="favoritos">Selecionado</button>
      </div>
    </div>

    <div id="cards-container" class="d-none">
      <h2>Cards</h2>
      <div class="row" id="card-list">
        <?php foreach ($cards as $card): ?>
          <div class="col-sm-4 card-item" data-card-id="<?php echo $card['id']; ?>">
            <div class="card">
              <div class="card-body">
                <button style="float: right;" class="btn btn-link btn-favorito"><i class="fas fa-star"></i></button>
                <h5 class="card-title">
                  <?php echo $card['name']; ?>
                </h5>
                <p class="card-text">Conteúdo do
                  <?php echo $card['tags']; ?>.
                </p>
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
  
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
<script>
  document.addEventListener("DOMContentLoaded", function () {
    var verCardsButton = document.getElementById("ver-cards");
    var favoritosButton = document.getElementById("favoritos");
    var cardsContainer = document.getElementById("cards-container");
    var favoritosContainer = document.getElementById("favoritos-container");
    var favoritoButtons = document.querySelectorAll(".btn-favorito");

    var isFavoritosView = false;
    var favoritosCookieName = "favoritos";

    verCardsButton.addEventListener("click", function () {
      isFavoritosView = false;
      cardsContainer.classList.remove("d-none");
      favoritosContainer.classList.add("d-none");
      verCardsButton.disabled = true;
      favoritosButton.disabled = false;
      showAllCards();
      favoritoButtons.forEach(function (button) {
        button.classList.remove("d-none");
      });
    });

    favoritosButton.addEventListener("click", function () {
      isFavoritosView = true;
      cardsContainer.classList.add("d-none");
      favoritosContainer.classList.remove("d-none");
      verCardsButton.disabled = false;
      favoritosButton.disabled = true;
      showFavoritoCards();
      favoritoButtons.forEach(function (button) {
        button.classList.add("d-none");
      });
    });

    favoritoButtons.forEach(function (button) {
      button.addEventListener("click", function () {
        if (!isFavoritosView) {
          var cardId = this.closest(".card-item").getAttribute("data-card-id");
          var isFavorito = this.classList.toggle("btn-favorito-selected");
          toggleFavorito(cardId, isFavorito);
        }
      });
    });

    function showAllCards() {
      var cards = document.querySelectorAll(".card-item");
      cards.forEach(function (card) {
        card.style.display = "block";
      });
    }

    function showFavoritoCards() {
      var favoritos = getFavoritos();
      var favoritosList = document.getElementById("favoritos-list");
      favoritosList.innerHTML = "";
      favoritos.forEach(function (cardId) {
        var card = document.querySelector('.card-item[data-card-id="' + cardId + '"]');
        if (card) {
          favoritosList.appendChild(card.cloneNode(true));
        }
      });
    }
      //Maximo de cards salvos
    function toggleFavorito(cardId, isFavorito) {
      var favoritos = getFavoritos();
      var index = favoritos.indexOf(cardId);
      if (isFavorito && index === -1 && favoritos.length < 3) {
        favoritos.push(cardId);
      } else if (!isFavorito && index !== -1) {
        favoritos.splice(index, 1);
      } else if (isFavorito && favoritos.length >= 3) {
        var button = document.querySelector('.card-item[data-card-id="' + cardId + '"] .btn-favorito');
        button.classList.remove('btn-favorito-selected');
        alert("Você atingiu o limite máximo de favoritos (3).");
        return;
      }
      setFavoritos(favoritos);
      if (isFavoritosView) {
        showFavoritoCards();
      }
    }

    function setFavoritos(favoritos) {
      var date = new Date();
      date.setTime(date.getTime() + 1 * 24 * 60 * 60 * 1000); // Duração de 1 dia
      var expires = "expires=" + date.toUTCString();
      var favoritosString = favoritos.join(",");
      document.cookie =
        favoritosCookieName + "=" + favoritosString + ";" + expires + ";path=/";
    }

    function getFavoritos() {
      var favoritosString = getCookie(favoritosCookieName);
      if (favoritosString) {
        return favoritosString.split(",");
      }
      return [];
    }

    function isFavoritoCard(cardId) {
      var favoritos = getFavoritos();
      return favoritos.includes(cardId);
    }

    function getCookie(name) {
      var cookieValue = document.cookie.match("(^|;)\\s*" + name + "\\s*=\\s*([^;]+)");
      return cookieValue ? cookieValue.pop() : "";
    }

    // Restaura o estado dos botões de favorito ao recarregar a página
    favoritoButtons.forEach(function (button) {
      var cardId = button.closest(".card-item").getAttribute("data-card-id");
      var isFavorito = isFavoritoCard(cardId);
      if (isFavorito) {
        button.classList.add("btn-favorito-selected");
      }
    });

    if (isFavoritosView) {
      showFavoritoCards();
    }
  });
</script>

</body>

</html>
