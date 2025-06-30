// itemExport.js
document.addEventListener('DOMContentLoaded', function() {
    const { jsPDF } = window.jspdf;

    // Configuración mejorada para PDF
    const PDF_CONFIG = {
        pageSize: 'a4',
        margin: 15,
        marginBottom: 60,
        maxWidth: 180,
        quality: 2,
        dpi: 300,
        fontScale: 0.85
    };

    // Función mejorada para generar contenido
    function generatePrintContent(forPDF = false) {
        const item = {
            nombre: document.querySelector('h2').textContent,
            activoFijo: document.querySelector('p.text-gray-600').textContent,
            status: document.querySelector('span').textContent.trim(),
            statusClass: document.querySelector('span').className,
            details: Array.from(document.querySelectorAll('.detail-field')).map(field => ({
                title: field.querySelector('h3').textContent,
                value: field.querySelector('p').textContent
            }))
        };

        return `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Detalle del Ítem - ${item.nombre}</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: ${forPDF ? '5mm' : '15px'};
                        color: #333;
                        font-size: ${forPDF ? PDF_CONFIG.fontScale : 1}rem;
                        line-height: 1.4;
                    }
                    .print-container {
                        width: ${forPDF ? PDF_CONFIG.maxWidth + 'mm' : '800px'};
                        margin: 0 auto;
                    }
                    .header {
                        border-bottom: 1px solid #3B82F6;
                        padding-bottom: 4px;
                        margin-bottom: 8px;
                    }
                    .title {
                        font-size: ${forPDF ? 1.3 * PDF_CONFIG.fontScale : 1.2}rem;
                        font-weight: bold;
                        color: #111;
                        margin-bottom: 3px;
                    }
                    .subtitle {
                        font-size: ${forPDF ? 0.9 * PDF_CONFIG.fontScale : 0.9}rem;
                        color: #666;
                    }
                    .status {
                        display: inline-block;
                        padding: 2px 8px;
                        border-radius: 9999px;
                        font-size: ${0.8 * (forPDF ? PDF_CONFIG.fontScale : 1)}rem;
                        font-weight: 600;
                        ${item.statusClass.includes('green') ? 'background-color: #D1FAE5; color: #065F46;' : ''}
                        ${item.statusClass.includes('red') ? 'background-color: #FEE2E2; color: #B91C1C;' : ''}
                        ${item.statusClass.includes('yellow') ? 'background-color: #FEF3C7; color: #92400E;' : ''}
                        ${item.statusClass.includes('gray') ? 'background-color: #F3F4F6; color: #374151;' : ''}
                    }
                    .detail-grid {
                        display: grid;
                        grid-template-columns: repeat(3, 1fr);
                        gap: ${forPDF ? '6px' : '10px'};
                    }
                    .detail-item {
                        margin-bottom: ${forPDF ? '8px' : '12px'};
                        page-break-inside: avoid;
                    }
                    .detail-title {
                        font-size: ${0.8 * (forPDF ? PDF_CONFIG.fontScale : 1)}rem;
                        font-weight: 600;
                        color: #6B7280;
                        text-transform: uppercase;
                        margin-bottom: 2px;
                    }
                    .detail-value {
                        font-size: ${0.9 * (forPDF ? PDF_CONFIG.fontScale : 1)}rem;
                        word-break: break-word;
                    }
                    .full-width {
                        grid-column: span 3;
                    }
                    .footer {
                        margin-top: 15px;
                        padding-bottom: 10px;
                        text-align: center;
                        color: #6B7280;
                        font-size: ${0.6 * (forPDF ? PDF_CONFIG.fontScale : 1)}rem;
                    }
                </style>
            </head>
            <body>
                <div class="print-container">
                    <div class="header">
                        <div class="title">${item.nombre}</div>
                        <div class="subtitle">${item.activoFijo} <span class="status">${item.status}</span></div>
                    </div>
                    
                    <div class="detail-grid">
                        ${item.details.map(detail => `
                            <div class="detail-item ${detail.title.includes('Descripción') || detail.title.includes('Observaciones') ? 'full-width' : ''}">
                                <div class="detail-title">${detail.title}</div>
                                <div class="detail-value">${detail.value}</div>
                            </div>
                        `).join('')}
                    </div>
                    
                    <div class="footer">
                        Documento generado el ${new Date().toLocaleDateString()} a las ${new Date().toLocaleTimeString()}
                    </div>
                </div>
            </body>
            </html>
        `;
    }

    // Función para imprimir (ahora correctamente expuesta)
    window.printItem = function() {
        const printContent = generatePrintContent(false);
        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();
        
        printWindow.onload = function() {
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        };
    };

    // Función para exportar a PDF
    window.exportToPDF = async function() {
        const loader = showLoader();
        
        try {
            const pdf = new jsPDF('p', 'mm', PDF_CONFIG.pageSize);
            const content = generatePrintContent(true);
            
            const tempDiv = document.createElement('div');
            tempDiv.style.position = 'absolute';
            tempDiv.style.left = '-9999px';
            tempDiv.style.width = `${PDF_CONFIG.maxWidth}mm`;
            tempDiv.style.background = 'white';
            tempDiv.innerHTML = content;
            document.body.appendChild(tempDiv);
            
            const canvasOptions = {
                scale: PDF_CONFIG.quality,
                dpi: PDF_CONFIG.dpi,
                logging: false,
                useCORS: true,
                allowTaint: true,
                letterRendering: true,
                windowWidth: Math.round(PDF_CONFIG.maxWidth * 3.77),
                width: Math.round(PDF_CONFIG.maxWidth * 3.77),
                height: tempDiv.scrollHeight,
                scrollX: 0,
                scrollY: 0
            };
            
            const canvas = await html2canvas(tempDiv, canvasOptions);
            document.body.removeChild(tempDiv);
            
            const imgWidth = PDF_CONFIG.maxWidth;
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            
            pdf.addImage(canvas, 'PNG', 
                PDF_CONFIG.margin, 
                PDF_CONFIG.margin, 
                imgWidth, 
                imgHeight);
            
            pdf.save(`Detalle_Item_${document.querySelector('h2').textContent.trim()}.pdf`);
            
        } catch (error) {
            console.error('Error al generar PDF:', error);
            alert('Ocurrió un error al generar el PDF');
        } finally {
            hideLoader(loader);
        }
    };

    function showLoader() {
        const loader = document.createElement('div');
        loader.style.position = 'fixed';
        loader.style.top = '0';
        loader.style.left = '0';
        loader.style.width = '100%';
        loader.style.height = '100%';
        loader.style.backgroundColor = 'rgba(0,0,0,0.5)';
        loader.style.display = 'flex';
        loader.style.justifyContent = 'center';
        loader.style.alignItems = 'center';
        loader.style.zIndex = '9999';
        loader.innerHTML = `
            <div style="background: white; padding: 20px; border-radius: 8px; text-align: center;">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
                <p class="mt-3 text-gray-700">Generando documento...</p>
            </div>
        `;
        document.body.appendChild(loader);
        return loader;
    }

    function hideLoader(loader) {
        if (loader && loader.parentNode) {
            document.body.removeChild(loader);
        }
    }
});