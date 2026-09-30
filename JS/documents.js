async function loadGlobalDocumentHistory() {
    const displayArea = document.getElementById('globalDocContainer');
    displayArea.innerHTML = "Loading history...";

    try {
        const response = await fetch('../php/get_all_user_docs.php');
        const applications = await response.json();

        if (applications.length === 0) {
            displayArea.innerHTML = "<p>No documents found for this account.</p>";
            return;
        }

        displayArea.innerHTML = ""; // Clear loader

        applications.forEach(app => {
            // Create a small header for each separate application (Tag)
            const sectionHeader = document.createElement('p');
            sectionHeader.style = "font-weight: bold; font-size: 20px; color: #333; background: #f4f4f4; padding: 8px; border-left: 4px solid #007bff; margin-top: 15px; font-family: sans-serif, Berlin Sans FB,arial;";
            sectionHeader.innerHTML = `📁 Application ID: ${app.tag} — ${app.dat1 || 'Untitled'}`;
            displayArea.appendChild(sectionHeader);

            // Loop through the file slots (dat6 to dat28)
            for (let i = 6; i <= 28; i++) {
                let filePath = app['dat' + i];
                // Focus ONLY on the filename from the database
                let fileName = app['dat' + i + '_name'] || `Document_${i}.pdf`;
                let status = parseInt(app['dat' + i + '_status']) || 0;

                if (filePath && filePath !== "") {
                    const fileRow = document.createElement('div');
                    fileRow.style = "display: flex; justify-content: space-between; padding: 5px 15px; border-bottom: 1px thin #eee; font-size: 0.9em;";

                    // Define Status Text
                    let statusLabel = '<span style="color:orange;">Pending</span>';
                    if (status === 1) statusLabel = '<span style="color:green;">Accepted</span>';
                    if (status === 2) statusLabel = '<span style="color:red;">Rejected</span>';

                    // Removed ${label} and kept only the 📄 icon and filename
                    fileRow.innerHTML = `
                        <span>📄 <a href="${filePath}" target="_blank" style="color: blue; font-size:16px; font-family: sans-serif, Berlin Sans FB,arial; text-decoration: none;">${fileName}</a></span>
                        <span>${statusLabel}</span>
                    `;
                    displayArea.appendChild(fileRow);
                }
            }
        });

    } catch (error) {
        displayArea.innerHTML = "Error loading documents.";
        console.error(error);
    }
}