// Ensure that script runs when DOM is fully loaded
window.onload = function () {
  startTime();
  createMedicationSelect();
  createExaminationSelect();
  calculateTotalPrice(); // Calculate total price on load
  previousConsultations();

  // Add event listener to the addMedicationButton
  document
    .getElementById("addMedicationButton")
    .addEventListener("click", function (event) {
      event.preventDefault();
      createMedicationSelect();
    });

  // Add event listener to the addExaminationButton
  document
    .getElementById("addExaminationButton")
    .addEventListener("click", function (event) {
      event.preventDefault();
      createExaminationSelect();
      calculateTotalPrice(); // Update total price after adding examination
    });

  // Add event listener to update the total price when an examination is removed
  document
    .getElementById("examinationsContainer")
    .addEventListener("click", function (event) {
      if (event.target && event.target.classList.contains("remove-button")) {
        calculateTotalPrice(); // Update total price after removing examination
      }
    });
};

// Clock script
function startTime() {
  let m = 0;
  let s = 0;

  function updateTime() {
    s++;
    if (s === 60) {
      s = 0;
      m++;
    }
    const displayM = checkTime(m);
    const displayS = checkTime(s);
    document.getElementById("timeConsultation").innerText =
      displayM + ":" + displayS;
    setTimeout(updateTime, 1000);
  }

  updateTime();
}

function checkTime(i) {
  return i < 10 ? "0" + i : i;
}

// Function to create a new select element with medications
function createMedicationSelect() {
  // Get the container
  const container = document.getElementById("medicationsContainer");

  if (!container) {
    console.error("Container element 'medicationsContainer' not found.");
    return;
  }

  // Create a new div to hold the select element and the remove button
  const wrapper = document.createElement("div");
  wrapper.classList.add("medication-wrapper");

  // Create a new select element
  const selectElement = document.createElement("select");
  selectElement.setAttribute("name", "medications[]");

  // Create default option
  const defaultOption = document.createElement("option");
  defaultOption.textContent = "-- Selecteer medicatie --";
  defaultOption.setAttribute("selected", "selected");
  defaultOption.setAttribute("disabled", "disabled");
  selectElement.appendChild(defaultOption);

  // Populate the select element with options
  medications.forEach((medication) => {
    const option = document.createElement("option");
    option.value = medication.id;
    option.textContent = `${medication.name} (${medication.dosage}) (${medication.description})`;
    selectElement.appendChild(option);
  });

  // Create a remove button
  const removeButton = document.createElement("button");
  removeButton.textContent = "Remove";
  removeButton.type = "button";
  removeButton.classList.add("remove-button");
  removeButton.addEventListener("click", function () {
    container.removeChild(wrapper);
  });

  // Append the select element and the remove button to the wrapper
  wrapper.appendChild(selectElement);
  wrapper.appendChild(removeButton);

  // Append the wrapper to the container
  container.appendChild(wrapper);
}

// Function to create a new select element with examinations
function createExaminationSelect() {
  // Get the container
  const container = document.getElementById("examinationsContainer");

  if (!container) {
    console.error("Container element 'examinationsContainer' not found.");
    return;
  }

  // Create a new div to hold the select element and the remove button
  const wrapper = document.createElement("div");
  wrapper.classList.add("examination-wrapper");

  // Create a new select element
  const selectElement = document.createElement("select");
  selectElement.setAttribute("name", "examinations[]");

  // Create default option
  const defaultOption = document.createElement("option");
  defaultOption.textContent = "-- Selecteer onderzoek --";
  defaultOption.setAttribute("selected", "selected");
  defaultOption.setAttribute("disabled", "disabled");
  selectElement.appendChild(defaultOption);

  // Populate the select element with options
  examinations.forEach((examination) => {
    const option = document.createElement("option");
    option.value = examination.id;
    option.textContent = examination.description;
    option.dataset.price = examination.price; // Store price in data attribute
    option.dataset.taxpatient = examination.taxpatient;
    option.dataset.taxriziv = examination.taxriziv;
    selectElement.appendChild(option);
  });

  // Add event listener to update total price when an examination is selected
  selectElement.addEventListener("change", calculateTotalPrice);

  // Create a remove button
  const removeButton = document.createElement("button");
  removeButton.textContent = "Remove";
  removeButton.type = "button";
  removeButton.classList.add("remove-button");
  removeButton.addEventListener("click", function () {
    container.removeChild(wrapper);
    calculateTotalPrice(); // Update total price after removing examination
  });

  // Append the select element and the remove button to the wrapper
  wrapper.appendChild(selectElement);
  wrapper.appendChild(removeButton);

  // Append the wrapper to the container
  container.appendChild(wrapper);
}

// Function to calculate total price of selected examinations
function calculateTotalPrice() {
    const selectElements = document.querySelectorAll(".examination-wrapper select");

    // Add event listener on change to update price
    selectElements.forEach((select) => {
        select.onchange = function () {
            updateTotalPrice();
        };
    });

    function updateTotalPrice() {
        let totalPrice = 0;
        let pricePatient = 0;
        selectElements.forEach((select) => {
            const selectedOption = select.options[select.selectedIndex];
            const selectedPrice = parseFloat(selectedOption.getAttribute("data-price"));
            const taxpatient = parseInt(selectedOption.getAttribute("data-taxpatient"));
            if (!isNaN(selectedPrice)) {
                totalPrice += selectedPrice;
                pricePatient += selectedPrice *(taxpatient/100);
            }
        });
        document.getElementById("fullprice").value = totalPrice.toFixed(2);
        document.getElementById("price").value = pricePatient.toFixed(2);
    }

    // Update the price field initially
    updateTotalPrice();
}

// Consultation view: 
function previousConsultations() {
  const toggleAllButton = document.getElementById('toggle-all-consultations');
  const previousConsultationsDiv = document.getElementById('previous-consultations');
  const toggles = document.querySelectorAll('.previous-consultation .toggle-button');

  toggleAllButton.addEventListener('click', function () {
      if (previousConsultationsDiv.style.display === 'none') {
          previousConsultationsDiv.style.display = 'block';
          this.textContent = 'Verberg eerdere consultaties';
      } else {
          previousConsultationsDiv.style.display = 'none';
          this.textContent = 'Toon eerdere consultaties';
      }
  });

  toggles.forEach(toggle => {
      toggle.addEventListener('click', function () {
          const details = this.nextElementSibling;
          if (details.style.display === 'block') {
              details.style.display = 'none';
          } else {
              details.style.display = 'block';
          }
      });
  });
}
