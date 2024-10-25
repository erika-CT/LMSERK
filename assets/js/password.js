$('span.password-icon').each((i,e)  =>{
   e.addEventListener('click',(r) =>{
        if($(e).hasClass('ocultar')){
            $(e).removeClass('ocultar').next().attr('type','password');
        } else {
            $(e).addClass('ocultar').next().attr('type','text');
        }
   });
  })
