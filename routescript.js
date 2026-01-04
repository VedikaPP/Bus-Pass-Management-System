
        function updateDriverDetails() {
            const busSelect = document.getElementById('bus-select');
            const driverNameInput = document.getElementById('driver-name');
            const driverNumberInput = document.getElementById('driver-number');
            const selectedOption = busSelect.options[busSelect.selectedIndex];

            if (selectedOption.value) {
                const driverName = selectedOption.getAttribute('data-driver');
                const driverNumber = selectedOption.getAttribute('data-number');
                driverNameInput.value = driverName;
                driverNumberInput.value = driverNumber;
                showBusStops(); // Automatically show bus stops when selection changes
            } else {
                driverNameInput.value = '';
                driverNumberInput.value = '';
                document.getElementById('bus-stops').innerHTML = ''; // Clear stops if no bus selected
            }
        }

        const busStopsData = {
            bus1: [
                { stop: "Wai", time: "7:00 AM" },
                { stop: "Bhuinj", time: "7:05 AM" },
                { stop: "Pachwad", time: "7:10 AM" },
                { stop: "Udtare", time: "7:15 AM" },
                { stop: "Anewadi", time: "7:45 AM" },
                { stop: "Wdhefata", time: "8:00 AM" },
                { stop: "Sadarbazar", time: "8:15 AM" },
                { stop: "PowaiNaka", time: "8:45 AM" },
                { stop: "DIET", time: "9:00 AM" }
            ],
            bus2: [
                { stop: "Satara satand", time: "8:20 AM" },
                { stop: "powaiNaka", time: "8:25 AM" },
                { stop: "Kamanihaudh", time: "8:30 AM"},
                { stop: "Rajwada", time: "8:30 AM" },
                { stop: "Samarth Mandir", time: "8:35 AM" },
                { stop: "bhondavde", time: "8:35 AM" }
                
            ], 
             
            bus3: [
                { stop: "Kelghar", time: "7:45 AM" },
                { stop: "Medha", time: "7:50 AM" },
                { stop: "Kondave", time: "8:05 AM" },
                { stop: "Molacha Odha", time: "8:25 AM" },
                { stop: "Shahupuri", time: "8:35 AM" },
                { stop: "rajwada", time: "8:45 AM" },
                { stop: "Diet", time: "9:05 AM" }




            ],
            bus4: [
                { stop: "Karanje naka", time: "7:45 AM" },
                { stop: "Radhika rod", time: "7:50 AM" },
                { stop: "rajwada", time: "8:00 AM" },
                { stop: "samarth mandir", time: "8:10 AM" },
                { stop: "Bhondavde", time: "8:30 AM" },
                { stop: "Diet", time: "8:50 AM" }



            ],
            bus5: [
                { stop: "kumte fata", time: "7:50 AM" },
                { stop: "koregav", time: "8:00 AM" },
                { stop: "Triputi", time: "8:10 AM" },
                { stop: "bombe restorunt", time: "8:25 AM" },
                { stop: "powai naka", time: "8::35 AM" },
                { stop: "Machi pet", time: "8:40 AM" },
                { stop: "Diet", time: "9:05 AM" }
                
            ],
            bus6: [
                { stop: "umbraj", time: "8:05 AM" },
                { stop: "kashil", time: "8:35 AM" },
                { stop: "Atit", time: "9:05 AM" },
                { stop: "nagthane", time: "9:05 AM" },
                { stop: "Borgav", time: "9:05 AM" },
                { stop: "shendre", time: "9:05 AM" },
                { stop: "Diet", time: "9:05 AM" }
            ],
            bus7: [
                { stop: "Rahimatpur", time: "7:50 AM" },
                { stop: "Dhamner", time: "8:10 AM" },
                { stop: "Tasgav", time: "9:20AM" },
                { stop: "Chinchner", time: "8:25 AM" },
                { stop: "MIDC", time: "8:30 AM" },
                { stop: "Ajinta", time: "8:35 AM" },
                { stop: "Powai naka", time: "8:40 AM" },
                { stop: "DIET", time: "9:05 AM" }

            ],
            bus8: [
                { stop: "Mandave", time: "7:45 AM" },
                { stop: "Ninam", time: "7:50 AM" },
                { stop: "padli", time: "8:00 AM" },
                { stop: "Nagthene", time: "8:10 AM" },
                { stop: "shendre", time: "8:20 AM" },
                { stop: "songav fata", time: "8:30 AM" },
                { stop: "Diet", time: "8:50 AM" }
            ],
        };

        function showBusStops() {
    const busSelect = document.getElementById("bus-select");
    const selectedBus = busSelect.value;
    const busStopsDiv = document.getElementById("bus-stops");

    busStopsDiv.innerHTML = '';

    if (selectedBus) {
        const stops = busStopsData[selectedBus];

        if (stops) {
            let stopsHtml = `<h1>Stops for ${selectedBus.charAt(0).toUpperCase() + selectedBus.slice(1)}</h1>`;

            // Downward flow with arrows
            stopsHtml += '<div class="downward-flow">';
            stops.forEach(stop => {
                stopsHtml += `
                    <div class="bus-stop">
                        <div class="arrow-down"></div>
                        <div class="bus-stop-text">${stop.stop} - ${stop.time}</div>
                    </div>`;
            });
            stopsHtml += '</div>';

            // Upward flow with arrows (reversed)
            stopsHtml += `<h5 style="margin-top: 40px;">Return Stops</h5>`;
            stopsHtml += '<div class="upward-flow">';
            for (let i = stops.length - 1; i >= 0; i--) {
                stopsHtml += `
                    <div class="bus-stop">
                        <div class="arrow-up"></div>
                        <div class="bus-stop-text">${stops[i].stop} - ${stops[i].time}</div>
                    </div>`;
            }
            stopsHtml += '</div>';

            busStopsDiv.innerHTML = stopsHtml;
        }
    }
}
