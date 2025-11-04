console.log("hdn;ksnfskjdv")
function main() {
    console.log("work")
    const logBut = document.getElementById("logBut");
    const signBut = document.getElementById("signBut");
    const inlogForm = document.getElementById("login");
    const aanmeldForm = document.getElementById("aanmelden");

    if (!logBut || !signBut || !inlogForm || !aanmeldForm) return;

    logBut.addEventListener("click", (e) => {
        e.preventDefault();
        inlogForm.style.display = "none";
        aanmeldForm.style.display = "block";
    }); 

    signBut.addEventListener("click", (e) => {
        e.preventDefault();
        aanmeldForm.style.display = "none";
        inlogForm.style.display = "block";
    });



}
main();

const gegevensTrigger = document.getElementById("gegevens");
const changeInfoPanel = document.getElementById("changeinfo");

if (gegevensTrigger && changeInfoPanel) {
    gegevensTrigger.addEventListener("click", () => {
        changeInfoPanel.style.display = "block";
    });
}