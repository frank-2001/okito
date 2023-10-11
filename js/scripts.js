/*!
* Start Bootstrap - Shop Homepage v5.0.6 (https://startbootstrap.com/template/shop-homepage)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-shop-homepage/blob/master/LICENSE)
*/
// This file is intentionally blank
// Use this file to add JavaScript to your project
$.get("server/", {"houses-all":""},
    function (data, textStatus, jqXHR) {
        data.forEach(element => {
            content=   `
            <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Product image-->
                            <img class="card-img-top" src="server/images/`+element.image+`" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">`+element.adresse+`</h5>
                                    <!-- Product price-->
                                    Prix: `+element.prix+` <br>
                                    `+element.description+`
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                               
                                <div onclick="inter(`+element.id+`)" class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">En savoir plus</a></div>
                            </div>
                        </div>
                    </div>
            `
            
            $("#houses").append(content);
        });
         
    },
    "json"
);

function inter(id) {
    let number="+243970923033"
    let message="Salut je suis interesse par la maison ID "+id
    let url = "https://api.whatsapp.com/send/?phone=" + number + "&text=" + message + "&type=phone_number&app_absent=0"
    window.open(url);
}