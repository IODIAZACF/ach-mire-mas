docker run -d --name wine-server --restart=unless-stopped \
    -v /opt/lampp/htdocs/herramientas/reporte/:/app/ \
    -v /opt/lampp/htdocs/herramientas/reporte/windows_fonts:/root/.fonts/ttf \
        -v /opt/lampp/htdocs/herramientas/reporte/report.conf:/etc/supervisor/conf.d/report.conf \
        -p 9997:80 \
        seancheung/alpinewine:wine
