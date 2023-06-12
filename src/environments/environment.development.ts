export const environment = {
    production: false,
    baseUrl: "http://localhost/projecte-daw/backend_php/api/",
    apiUrl: {
        login: "login/login.php",
        logout: "login/logout.php",
        import: 'importacio/import.php',
        users: 'users/users.php',
        addUsers: "users/addUsers.php",
        deleteUsers: "users/deleteUsers.php",
        readPersones: "persones/readPersones.php",
        buscarPersones: "persones/search.php",
        numPagines: "persones/numPagines.php",
        crearPersona: "persones/createPersona.php",
        classes: "persones/classes.php",
        crearEspai: "espais/createEspai.php",
        readEspais: "espais/readEspais.php",
        buscarEspais: "espais/search.php",
        numPaginesEspai: "espais/numPagines.php",
        crearDispositiu: "dispositius/createDispositiu.php",
        readDispositiu: "dispositius/readDispositius.php",
        buscarDispositius: "dispositius/search.php",
        numPaginesDispositiu: "dispositius/numPagines.php",
        infoSelects: "dispositius/infoSelects.php",
    }
};
