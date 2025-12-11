<?php

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Actividades - KaiAdmin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
    
    <div class="container mx-auto p-4 md:p-6 max-w-7xl">
        
       

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Formulario -->
            <div class="lg:col-span-2" id="formContainer">
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-indigo-100">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-file-alt text-indigo-600 mr-2"></i>
                        Registrar Nueva Actividad
                    </h2>
                    
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="space-y-6">
                        <input type="hidden" name="action" value="add">
                        
                        <!-- Título -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nombre de la Actividad *
                            </label>
                            <input 
                                type="text" 
                                name="title" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none"
                                placeholder="Ej: peces">
                        </div>

                        <!-- Estado de la actividad -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Estado de la Actividad *
                            </label>
                            <input 
                                type="text" 
                                name="status" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none"
                                placeholder="Ej: Pendiente, En Progreso, Completada">
                        </div>

                        <!-- Botón Guardar -->
                        <button 
                            type="submit"
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i>Guardar Actividad
                        </button>
                    </form>
                </div>
            </div>

          
    
</body>
</html>