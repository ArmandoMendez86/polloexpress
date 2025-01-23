<head>
    <title>Ingresar | &#x1F60E;</title>
</head>

<style>
    .login-fondo {
    background-image: url('assets/img/login.webp');
    background-size: cover;
    background-position: center;
    background-blend-mode: overlay;
    background-color: rgba(0, 0, 0, 0.5); 
}

</style>

<div class="row justify-content-center align-items-center min-vh-100 login-fondo">
    <div class="col-lg-6">
        <div class="box login mx-auto" style="max-width: 500px; background-color:#f5f8fd7a" >
            <h3>Identificate</h3>
            <form >
                <input type="text" name="usuario" placeholder="Usuario" id="usuario">
                <input type="password" name="password" placeholder="Contraseña" id="password">
                <button type="button" id="ingresar" class="button">Ingresar</button>
            </form>
        </div>
    </div>
</div>