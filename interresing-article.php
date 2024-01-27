

<div class="div-click1">
        <button class="click1">Je suis intérressé</button>
    </div>
    <div class="form">
    <form method="POST" id="formOccas">
    <input type="hidden" name="form_type" value="formOccas">            <hr>
            <div class="username-div">
                <div>
                    <label for="name">Nom</label>
                    <input type="text" name="name" id="name" placeholder="Nom">
                </div>
                <div>
                    <label for="firstname">Prénom</label>
                    <input type="text" name="firstname" id="firstname" placeholder="Prénom">
                </div>
            </div>
            <div class="username-div">
                <div>
                    <label for="email">Adresse mail</label>
                    <input type="email" name="email" id="email" placeholder="Email">
                </div>
                <div>
                    <label for="num">Numéro de téléphone</label>
                    <input type="tel" name="num" id="num" placeholder="Numéro de téléphone">
                </div>
            </div>
            <div class="username-div">
                <div>
                    <label for="message">Message</label>
                    <textarea name="message">Bonjour, <?= $article['nom'] . ' (' . $article['details'].')' ?> m'intérresse. Je vous envoie donc mes informations.
</textarea>

                </div>

                <input type="submit" value="Envoyer" class="click2">
            </div>
        </form>
    </div>

    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
    <script>
        $(document).ready(() => {
            $('.form').hide();

            $('.click1').click(function(){
                $('.form').fadeIn(500);
                $('.div-click1').hide();
            });
        });
    </script>


<style>
        .form {
            display: flex;
            justify-content: center;
            margin: 5%;
        }

        form {
            display: flex;
            flex-direction: column;
            background-color: #fff;
            padding: 10px;
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            width: 50%;
        }

        h4 {
            text-align: center;
            font-size: 20px;
            background-color: #17a2b8;
            padding: 10px;
            border-radius: 7px;
        }

        hr {
            margin: 10px 0;
            background-color: #ccc;
            border: 0;
            height: 1px;
            width: 100%;
        }

        .username-div {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .username-div div {
            display: flex;
            flex-direction: column;
            width: 49%;
        }

        label {
            margin-bottom: 6px;
            color: #17a2b8;
        }

        input,
        textarea {
            margin-bottom: 5px;
            padding: 10px;
            outline: 0;
            border: 1px solid rgba(0, 0, 0, 0.4);
            border-radius: 6px;
            width: 100%;
            font-size: 14px;
        }

        input:focus,
        textarea:focus {
            border: 1px solid #17a2b8;
        }

        input[type="button"] {
            margin-top: 15px;
            background-color: #17a2b8;
            color: #fff;
            border: 1px solid #17a2b8;
            cursor: pointer;
            width: 20%;
        }

        #box-p {
            background-color: #2E7E32;
            color: #fff;
            padding: 20px;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
            width: 20%;
            border-radius: 15px;
            text-align: center;
            font-size: 20px;
        }

        .div-click1 {
            text-align: center;
        }

        .click1 {
            margin: 1.2em;
            padding: 10px;
            border-radius: 15px;
            background-color: #17a2b8;
            color: #fff;
            font-size: 18px;
        }

        /* Media Queries */
        @media only screen and (max-width: 768px) {
            form {
                width: 80%;
            }

            .username-div div {
                width: 100%;
            }

            input[type="button"] {
                width: 50%;
                margin-left: auto;
                margin-right: auto;
            }

            #box-p {
                width: 80%;
                justify-content: center;
                padding: auto;
            }
        }

        @media only screen and (max-width: 480px) {
            form {
                width: 90%;
            }

            input,
            textarea {
                font-size: 12px;
            }

            .username-div div {
                width: 100%;
            }

            input[type="button"] {
                width: 70%;
                margin-left: auto;
                margin-right: auto;
            }

            #box-p {
                width: 80%;
                justify-content: center;
                padding: auto;
            }
        }
    </style>
