<?php 

include_once('header.php');


?>

<div class="col-lg-6 col-md-8 col-sm-12">
    <form method="POST" id="formContact" enctype="multipart/form-data" class="text-center">
        <div class="form-row">
            <div class="form-group mb-4">
                <label for="title">Titre :</label>
                <input type="text" class="form-control" name="title" required="">
            </div>
            <div class="form-group mb-4">
                <label for="details">Détails :</label>
                <textarea class="form-control" name="details" rows="4" required=""></textarea>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group mb-4">
                <label for="mail">Adresse mail :</label>
                <input type="email" class="form-control" name="mail" placeholder="Email" required="">
            </div>
            <div class="form-group mb-4">
                <label for="num">Téléphone :</label>
                <input type="tel" class="form-control" name="num" placeholder="Numéro de téléphone" required="">
            </div>
        </div>
        
            <div class="form-group mb-4">
                <textarea name="">
                    Ecrivez la demande souhaité avec son explication. Merci
                </textarea>
            </div>
        </div>
        
        <button type="submit" class="btn btn-success my-3">Envoyer la demande</button>
    </form>
</div>


<div class="div-form mt-4 ">
    <form method="POST" id="formContact">
    <input type="hidden" name="form_type" value="formContact">            
    <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name_Contact">Nom</label>
                    <input type="text" class="form-control" name="name_Contact" id="name" placeholder="Nom" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="firstname_Contact">Prénom</label>
                    <input type="text" class="form-control" name="firstname_Contact" id="firstname" placeholder="Prénom" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email_Contact">Adresse mail</label>
                    <input type="email" class="form-control" name="email_Contact" id="email" placeholder="Email" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="num_Contact">Téléphone</label>
                    <input type="tel" class="form-control" name="num_Contact" id="num" placeholder="Numéro de téléphone" required>
                </div>
            </div>
            <div class="form-group">
                <label for="message_Contact">Votre message</label>
                <textarea class="form-control" name="message_Contact" id="message" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-success text-center">Envoyer</button>
        </form>
    </div>
    <div class="form-contact-div-2 mt-4 text-center">
        <p>Merci pour votre demande, nous essayons de vous rappeler le plus vite possible</p>
    </div>
</div>



<!-- <script>
    $(() => {
        $('.div-form').hide();
        $('.form-contact-div-2').hide();

        $('.contact-button-click-1').click(function(){
            $('.div-form').slideDown();
            $('.form-contact-div-1').hide();
        });

        $('#formContact').submit(function(event) {
        event.preventDefault(); 
        $(this).slideUp();
        $('.form-contact-div-2').fadeIn(500,() => {
            setTimeout(() => {
                $('.form-contact-div-2').fadeOut(2000, () => {
                    event.target.submit();
                });
            }, 5000); 
       
        });
    });
    });
</script> -->
<style>
    .form-contact-div-2{
            background-color: #2E7E32;
            color: #fff;
            padding: 20px;
            border-radius: 15px;
            font-size: 20px;
        }
       form{
            width: 100%;
        }
        .form-row{
            margin: 0;
        }
</style>