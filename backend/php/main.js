function main() {
    const logBut = document.getElementById("logBut");
    const signBut = document.getElementById("signBut");
    const inlogForm = document.getElementById("login");
    const aanmeldForm = document.getElementById("aanmelden");

    if (logBut && signBut && inlogForm && aanmeldForm) {
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

    const gegevensTrigger = document.getElementById("gegevens");
    const changeInfoPanel = document.getElementById("changeinfo");
    const partijTrigger = document.getElementById("partij");
    const maakPartijPanel = document.getElementById("maakPartij");
    const mijnPartijPanel = document.getElementById("mijnPartij");

    if (gegevensTrigger && changeInfoPanel) {
        gegevensTrigger.addEventListener("click", () => {
            if (changeInfoPanel.style.display === "flex") {
                changeInfoPanel.style.display = "none";
                // Show mijnPartij again if it exists
                if (mijnPartijPanel) mijnPartijPanel.style.display = "block";
            } else {
                changeInfoPanel.style.display = "flex";
                if (maakPartijPanel) maakPartijPanel.style.display = "none";
                if (mijnPartijPanel) mijnPartijPanel.style.display = "none";
            }
        });
    }

    if (partijTrigger) {
        partijTrigger.addEventListener("click", () => {
            // Check which panel exists (maakPartij or mijnPartij)
            if (maakPartijPanel) {
                if (maakPartijPanel.style.display === "block") {
                    maakPartijPanel.style.display = "none";
                } else {
                    maakPartijPanel.style.display = "block";
                    if (changeInfoPanel) changeInfoPanel.style.display = "none";
                }
            } else if (mijnPartijPanel) {
                if (mijnPartijPanel.style.display === "block") {
                    mijnPartijPanel.style.display = "none";
                } else {
                    mijnPartijPanel.style.display = "block";
                    if (changeInfoPanel) changeInfoPanel.style.display = "none";
                }
            }
        });
    }

    // Navbar hide/show on scroll
    const navbar = document.getElementById('navbar');
    if (navbar) {
        let lastScrollTop = 0;
        let scrollTimeout;

        window.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scrolling down & past threshold
                    navbar.classList.add('hidden');
                } else {
                    // Scrolling up
                    navbar.classList.remove('hidden');
                }
                
                lastScrollTop = scrollTop;
            }, 10);
        });
    }
}
main();

// Image preview for partij foto
const partijFotoInput = document.getElementById('partij_foto');
const imagePreview = document.getElementById('imagePreview');
const previewImg = document.getElementById('previewImg');
const removeImageBtn = document.getElementById('removeImage');

if (partijFotoInput && imagePreview && previewImg) {
    partijFotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImg.src = event.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function() {
            partijFotoInput.value = '';
            previewImg.src = '';
            imagePreview.style.display = 'none';
        });
    }
}

// Image preview for edit partij foto
const editPartijFotoInput = document.getElementById('edit_partij_foto');
const editImagePreview = document.getElementById('editImagePreview');
const editPreviewImg = document.getElementById('editPreviewImg');
const removeEditImageBtn = document.getElementById('removeEditImage');

if (editPartijFotoInput && editImagePreview && editPreviewImg) {
    editPartijFotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                editPreviewImg.src = event.target.result;
                editImagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    if (removeEditImageBtn) {
        removeEditImageBtn.addEventListener('click', function() {
            editPartijFotoInput.value = '';
            editPreviewImg.src = '';
            editImagePreview.style.display = 'none';
        });
    }
}

// Toggle Pas Partij Aan form
const pasPartijAanBtn = document.getElementById('pasPartijAan');
const pasPartijAanForm = document.getElementById('pasPartijAanForm');
const mijnPartijDisplay = document.getElementById('mijnPartij');

if (pasPartijAanBtn && pasPartijAanForm && mijnPartijDisplay) {
    pasPartijAanBtn.addEventListener('click', function() {
        if (pasPartijAanForm.style.display === 'block') {
            pasPartijAanForm.style.display = 'none';
            mijnPartijDisplay.style.display = 'block';
        } else {
            pasPartijAanForm.style.display = 'block';
            mijnPartijDisplay.style.display = 'none';
        }
    });
}

// Toast notification function
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    if (!toast) return;
    
    toast.textContent = message;
    toast.className = 'toast show ' + type;
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 4000);
}