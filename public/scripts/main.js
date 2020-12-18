// Multiple images preview in browser
function imagesPreview(input, divGallery) {

    if (input.files) {
        var filesAmount = input.files.length;

        for (i = 0; i < filesAmount; i++) {
            var reader = new FileReader();

            reader.onload = function (event) {
                $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(divGallery);
            }

            reader.readAsDataURL(input.files[i]);
        }
    }

}

function showPending() {
    var x = document.getElementById("status_pending");
    var y = document.getElementById("status_approved");
    var z = document.getElementById("status_rejected");
        x.style.display = "block";
        y.style.display = "none";
        z.style.display = "none";
}

function showPublished() {
    var x = document.getElementById("status_pending");
    var y = document.getElementById("status_approved");
    var z = document.getElementById("status_rejected");
        x.style.display = "none";
        y.style.display = "block";
        z.style.display = "none";
}

function showRejected() {
    var x = document.getElementById("status_pending");
    var y = document.getElementById("status_approved");
    var z = document.getElementById("status_rejected");
        x.style.display = "none";
        y.style.display = "none";
        z.style.display = "block";
}

function confirmApprove(id)  {
    if (confirm("Do you really want to Approve this product directly?")) {
        document.location.href = "http://olx.xento.in/adminpanel/approve?id="+id;
        return true;
    }else   {
        document.location.href = 'http://olx.xento.in/adminpanel';
        return false;
    }
}

function confirmReject(id)  {
    if (confirm("Do you really want to Reject this product directly?")) {
        document.location.href = "http://olx.xento.in/adminpanel/reject?id="+id;
        return true;
    }else   {
        document.location.href = 'http://olx.xento.in/adminpanel';
        return false;
    }
}

function confirmDel(id)   {
    if (confirm("Do you really want to DELETE this product?")) {
        document.location.href = window.location.origin + "/myaccount/deleteAd?id="+id;
        return true;
    }else   {
        document.location.href = window.location.origin + '/myaccount';
        return false;
    }
}

setTimeout(function() {
    $('.alert-info').fadeOut('slow');
}, 2000);

setTimeout(function() {
    $('.alert-error').fadeOut('slow');
}, 2000);

setTimeout(function() {
    $('.database-connection-error').fadeIn('slow');
}, 2000);

setTimeout(function() {
    $('.database-connection-error').fadeOut('slow');
}, 5000);

function validatePassword(Password) {
    var regForPass = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]/;
  if (regForPass.test(Password.value)==false) {
    document.getElementById("passwordhelp").innerHTML="<br>Password must contain one Lowercase, Uppercase, Special character and one Digit atleast";
      return false;
  }else{
    document.getElementById("passwordhelp").innerHTML="";
    return true;
  }
}

function matchPass(Password1) {
    var password2 = document.getElementById("password").value;
    
    if (password2 != Password1.value) {
      document.getElementById("passwordhelp").innerHTML="Passwords are not same";
        return false;
    }else{
        document.getElementById("passwordhelp").innerHTML="";
    }
}

function maxiImg(img) {
    var expandImg = document.getElementById("main-img-pd");
    expandImg.src = img.src;
    expandImg.parentElement.style.display = "inline-block";
  }