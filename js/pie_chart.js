/* eventually this module should work by:
    - taking args (elementToSelect, dataToRender)
    - dataToRender will be passed from php MySQL query
    - this will require refactoring fileend.php into a fcn
*/
function drawPieChart(container, jsonData, colorData, d3 = window.d3) {
    console.log(jsonData);
    const w = '200';
    const h = '200';
    const r = Math.min(w, h)/2;
    
    // append color scheme to jsonData
    // for (let obj of jsonData) {
    //     obj.color = colorData[obj.label] || colorData[jsonData.indexOf(obj)];
    // }
    const color = d3.scaleOrdinal(Object.values(colorData));
    
    const svg = d3.select(container)
        .append('svg')
        .attr('width', w)
        .attr('height', h)
        .append('g')
        .attr('transform', `translate(${w/2},${h/2})`)
        
    const arc = d3.arc()
        .innerRadius(0)
        .outerRadius(r)
        
    var pie = d3.pie()
        .value(d => d.value)
        .sort(null)
        
    svg.selectAll('path')
        .data(pie(jsonData))
        .enter()
        .append('path')
        .attr('d', arc)
        .attr('fill', d => color(d.data.label))
}

function drawOpenCloseChart(d3, open, closed) {
    var openCloseData = [
        {label: 'open', count: open},
        {label: 'closed', count: closed}
    ]
    
    var container = document.getElementById('open-closed-graph')
    
    var width = "200"
    var height = "200"
    var radius = Math.min(width, height)/2
    
    var scheme = {
        red: '#d73027',
        green: '#58BF73'
    }
    var color = d3.scaleOrdinal(Object.values(scheme))
    
    var chart = d3.select(container)
        .append('svg')
        .attr('width', width)
        .attr('height', height)
        .append('g')
        .attr('transform', `translate(${width/2},${height/2})`)
        
    var arc = d3.arc()
        .innerRadius(0)
        .outerRadius(radius)
    
    var pie = d3.pie()
        .value(d => d.count)
        .sort(null)
        
    var path = chart.selectAll('path')
        .data(pie(openCloseData))
        .enter()
        .append('path')
        .attr('d', arc)
        .attr('fill', d => color(d.data.label))

    drawLegend(container, openCloseData, Object.values(scheme))
}

function drawSeverityChart(d3, block, crit, maj, min) {
    var severityData = [
        {label: 'blocker', count: block},
        {label: 'critical', count: crit},
        {label: 'major', count: maj},
        {label: 'minor', count: min}
    ]
    var scheme = {
        red: '#bd0026',
        redOrange: '#fc4e2a',
        orange: '#feb24c',
        yellow: '#ffeda0'
    }
    
    var container = document.getElementById('severity-graph')
    
    var width = '200'
    var height = '200'
    var radius = Math.min(width, height)/2
    
    var color = d3.scaleOrdinal(Object.values(scheme))
    
    var chart = d3.select(container)
        .append('svg')
        .attr('width', width)
        .attr('height', height)
        .append('g')
        .attr('transform', `translate(${width/2},${height/2})`)
        
    var arc = d3.arc()
        .innerRadius(0)
        .outerRadius(radius)
    
    var pie = d3.pie()
        .value(d => d.count)
        .sort(null)
        
    var path = chart.selectAll('path')
        .data(pie(severityData))
        .enter()
        .append('path')
        .attr('d', arc)
        .attr('fill', d => color(d.data.label))
        
    drawLegend(container, severityData, Object.values(scheme))
}

function drawLegend(container, data, colorScheme) {
    var legend = container.nextElementSibling

    data.forEach((datum, i) => {
        const label = legend.appendChild(document.createElement('span'))
        const swatch = document.createElement('i')
        
        label.classList.add('legend-label')
        label.textContent = datum.label

        swatch.classList.add('legend-swatch')
        swatch.style.backgroundColor = colorScheme[i]
        label.insertAdjacentElement('afterbegin', swatch)
    })
}
