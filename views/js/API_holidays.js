const obtenerFeriados = async (año = 2023) => {
    try {
      const response = await fetch('https://calendarific.com/api/v2/holidays?api_key=gjXsdYfiyt76HJ9AdkDmOScW6hnij4D7&country=MX&year='+ año +'&language=es');
      const data = await response.json();
      
      const diasNoLaborables = data.response.holidays
        .filter(feriado => 
          feriado.type.includes('National holiday') &&  // Días obligatorios por ley
          !feriado.type.includes('Observance')          // Excluir conmemoraciones no obligatorias
        )
        .map(feriado => ({
          nombre: traducirNombre(feriado.name),
          fecha: feriado.date.iso.split('T')[0],  // Formato YYYY-MM-DD
          tipo: 'Feriado nacional'
        }));
  
      console.log("Días no laborables por ley en México:", diasNoLaborables);
      return diasNoLaborables;
  
    } catch (error) {
      console.error('Error al obtener feriados:', error);
    }
  };
  
  function traducirNombre(nombreIngles) {    
    const traducciones = {
      'New Year\'s Day': 'Año Nuevo',
      'Constitution Day': 'Día de la Constitución',
      'Benito Juárez\'s Birthday': 'Natalicio de Benito Juárez',
      'Labor Day': 'Día del Trabajo',
      'Independence Day': 'Día de la Independencia',
      'Revolution Day': 'Día de la Revolución',
      'Christmas Day': 'Navidad'
      // Agrega más traducciones según necesites
    };
    
    return traducciones[nombreIngles] || nombreIngles;
  }
  