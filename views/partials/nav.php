<header class="py-2">
    <nav class="navbar navbar-expand-md navbar-dark px-4">
<!-- Logo and dropdown button-->
        <div class="logo"> 
            <a class="nav-link text-white" href="<?php echo root;?>"><img id="logo" src="<?php echo root;?>imgs/logo/logo2.png" alt="Logo" title="Página principal"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>  
        </div>   
<!-- Nav links -->        
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo root;?>random" title="Sugerencias">Sugerencias</a>
                </li>
                <li class="nav-item">                    
                    <a class="nav-link text-white" href="<?php echo root;?>custom-inclusive" title="Elegir por ingredientes">Incluir</a>
                </li>
                <li class="nav-item">                    
                    <a class="nav-link text-white" href="<?php echo root;?>custom-exclusive" title="Elegir por ingredientes">Excluir</a>
                </li>
                <li class="nav-item">                    
                    <a class="nav-link text-white" href="<?php echo root. "diet";?>" title="Elegir dieta">Dieta</a>
                </li>
                <li id="dropdownbtn" class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-gears text-white"></i>
                    </a>
                    <div id="dropdown-menu" class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">                    
                        <a class="dropdown-item" href="<?php echo root;?>ingredients" title="Ingredientes">Ingredientes</a>
                        <a class="dropdown-item" href="<?php echo root;?>add-recipe" title="Recetas">Recetas</a>
                        <a class="dropdown-item" href="<?php echo root;?>categories" title="Categorías">Categorías</a>
                    </div>
                </li>            
            </ul>
        </div>       
    </nav>
</header>
 <script>
deleteMessage("logout");  
hoverMenu();

//Delete message
function deleteMessage(button){
var deleteButtons = document.getElementsByClassName(button);

    for(var i = 0; i<deleteButtons.length; i++) {
        deleteButtons[i].addEventListener("click", function(event){    
            if(confirm("¿Desea salir?")) {
                return true;
            } else {
                event.preventDefault();
                return false;
            }
        })
    }
}

function hoverMenu(){
    var dropdownbtn = document.getElementById("dropdownbtn");
    var dropdown_menu =  document.getElementById("dropdown-menu");
    var other_btn = document.getElementById("other-btn");
    var other_menu = document.getElementById("other-menu");
    
    dropdownbtn.addEventListener("mouseover", function (event){
        dropdown_menu.style.display = "block";
    });
    dropdownbtn.addEventListener("mouseout", function (event){
        dropdown_menu.style.display = "none";
    });
    other_btn.addEventListener("mouseover", function (event){
        other_menu.style.display = "block";
    });
    other_btn.addEventListener("mouseout", function (event){
        other_menu.style.display = "none";
    });
}
</script>