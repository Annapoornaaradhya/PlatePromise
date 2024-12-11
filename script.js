// Pop-Up Image Viewer
document.querySelectorAll(".gallery img").forEach((image) => {
  image.addEventListener("click", () => {
    const popup = document.querySelector(".popup");
    const popupImage = popup.querySelector("img");
    popup.style.display = "flex";
    popupImage.src = image.src;
  });
});
document.querySelector(".popup span").addEventListener("click", () => {
  document.querySelector(".popup").style.display = "none";
});
