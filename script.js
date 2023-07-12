var verCardsButton = document.getElementById("ver-cards")
var favoritosButton = document.getElementById("favoritos")
var cardsContainer = document.getElementById("cards-container")
var favoritosContainer = document.getElementById("favoritos-container")
var favoritoButtons = document.querySelectorAll(".btn-favorito")

var isFavoritosView = false
var favoritosCookieName = "favoritos"

verCardsButton.addEventListener("click", function () {
  isFavoritosView = false
  cardsContainer.classList.remove("d-none")
  favoritosContainer.classList.add("d-none")
  verCardsButton.disabled = true
  favoritosButton.disabled = false
  showAllCards()
})

favoritosButton.addEventListener("click", function () {
  isFavoritosView = true
  cardsContainer.classList.add("d-none")
  favoritosContainer.classList.remove("d-none")
  verCardsButton.disabled = false
  favoritosButton.disabled = true
  showFavoritoCards()
})

favoritoButtons.forEach(function (button) {
  button.addEventListener("click", function () {
    if (!isFavoritosView) {
      var cardId = this.closest(".card-item").getAttribute("data-card-id")
      var isFavorito = this.classList.toggle("btn-favorito-selected")
      toggleFavorito(cardId, isFavorito)
    }
  })
})

function showAllCards() {
  var cards = document.querySelectorAll(".card-item")
  cards.forEach(function (card) {
    card.style.display = "block"
  })
}

function showFavoritoCards() {
  var favoritos = getFavoritos()
  var favoritosList = document.getElementById("favoritos-list")
  favoritosList.innerHTML = ""
  favoritos.forEach(function (cardId) {
    var card = document.querySelector(
      '.card-item[data-card-id="' + cardId + '"]'
    )
    if (card) {
      favoritosList.appendChild(card.cloneNode(true))
    }
  })
}

function toggleFavorito(cardId, isFavorito) {
  var favoritos = getFavoritos()
  var index = favoritos.indexOf(cardId)
  if (isFavorito && index === -1) {
    favoritos.push(cardId)
  } else if (!isFavorito && index !== -1) {
    favoritos.splice(index, 1)
  }
  setFavoritos(favoritos)
  if (isFavoritosView) {
    showFavoritoCards()
  }
}

function setFavoritos(favoritos) {
  var date = new Date()
  date.setTime(date.getTime() + 1 * 24 * 60 * 60 * 1000) // Duração de 1 dia
  var expires = "expires=" + date.toUTCString()
  var favoritosString = favoritos.join(",")
  document.cookie =
    favoritosCookieName + "=" + favoritosString + ";" + expires + ";path=/"
}

function getFavoritos() {
  var favoritosString = getCookie(favoritosCookieName)
  if (favoritosString) {
    return favoritosString.split(",")
  }
  return []
}

function isFavoritoCard(cardId) {
  var favoritos = getFavoritos()
  return favoritos.includes(cardId)
}

function getCookie(name) {
  var cookieValue = document.cookie.match(
    "(^|;)\\s*" + name + "\\s*=\\s*([^;]+)"
  )
  return cookieValue ? cookieValue.pop() : ""
}

// Restaura o estado dos botões de favorito ao recarregar a página
window.addEventListener("DOMContentLoaded", function () {
  favoritoButtons.forEach(function (button) {
    var cardId = button.closest(".card-item").getAttribute("data-card-id")
    var isFavorito = isFavoritoCard(cardId)
    if (isFavorito) {
      button.classList.add("btn-favorito-selected")
    }
  })

  if (isFavoritosView) {
    showFavoritoCards()
  }
})
