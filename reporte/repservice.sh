docker run -d --name wine-server --restart=unless-stopped \
	-v /opt/lampp/htdocs/reporte/:/app/ \
	-v /opt/lampp/htdocs/reporte/windows_fonts:/root/.fonts/ttf \
        -v /opt/lampp/htdocs/reporte/report.conf:/etc/supervisor/conf.d/report.conf \
        -p 9997:80 \
        seancheung/alpinewine:wine
