function togglePasswordVisibility(oeilId, mdpId) {
    const oeil = document.querySelector(oeilId);
    const mdp = document.querySelector(mdpId);
    const icone = oeil.querySelector('i');
  
    oeil.addEventListener('click', function() {
      if (mdp.getAttribute('type') === 'password') {
        mdp.setAttribute('type', 'text');
        icone.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        mdp.setAttribute('type', 'password');
        icone.classList.replace('fa-eye-slash', 'fa-eye');
      }
    });
}

if(document.querySelector("#oeil")) {
    togglePasswordVisibility('#oeil', '#mdp');
}

if(document.querySelector("#oeilActuelMdp")) {
    togglePasswordVisibility('#oeilActuelMdp', '#actuelMdp');
}

if(document.querySelector("#oeilNouveauMdp")) {
    togglePasswordVisibility('#oeilNouveauMdp', '#nouveauMdp');
}
  