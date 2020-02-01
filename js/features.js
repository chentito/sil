/* Evento que guarda el comentario */
document.getElementById( 'btnEnviaForm' ).addEventListener( 'click' , function( e ){
    e.preventDefault();
    var data = new FormData( document.getElementById( 'contactForm' ) );
    var headers = {};

    grecaptcha.ready(function() {
        grecaptcha.execute('6LesSrYUAAAAAAamrA1pKK8u7sjO4GXJL4yqm_8L', {action: 'add_comment'}).then(function(token) {
            data.append( 'g-recaptcha-response' , token );
            axios.post( '../../backend/send.php' , data , headers )
            .then( Response => {
                alert( JSON.stringify( Response.data ) );
            })
            .catch( err => {
                console.log( err );
            });

        });
    });
});


$(window).scroll( function(){
    if( $(this).scrollTop() > 5 ){
        $( '#sillogo' ).css( 'width' , '100px' );
    } else {
        $( '#sillogo' ).css( 'width' , '180px' );
    }
});