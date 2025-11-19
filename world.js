window.addEventListener("DOMContentLoaded", () => {
  const lookupCountryBtn = document.querySelector("#lookup");
  const lookupCitiesBtn = document.querySelector("#lookup-cities");
  const resultDiv = document.querySelector("#result");
  const input = document.querySelector("#country");

  async function runLookup(mode) {
    const country = input.value.trim();
    let url = "world.php";

    if (country) {
      if (mode === "cities") {
        url += `?country=${encodeURIComponent(country)}&lookup=cities`;
      } else {
        url += `?country=${encodeURIComponent(country)}`;
      }
    } else if (mode === "cities") {
      url += "?lookup=cities";
    }

    try {
      const res = await fetch(url);
      const html = await res.text();
      resultDiv.innerHTML = html;
    } catch (err) {
      resultDiv.innerHTML = `<p style="color:red;">Error: ${err.message}</p>`;
    }
  }

  lookupCountryBtn.addEventListener("click", () => runLookup("country"));
  lookupCitiesBtn.addEventListener("click", () => runLookup("cities"));
});
