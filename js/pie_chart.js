/* eventually this module should work by:
    - taking args (elementToSelect, dataToRender)
    - dataToRender will be passed from php MySQL query
    - this will require refactoring fileend.php into a fcn
*/
function drawOpenCloseChart(d3, open, closed) {
    console.log(open, closed)
    
    var openCloseData = [
        {label: 'open', count: open},
        {label: 'closed', count: closed}
    ]
    
    var container = document.getElementById('open-closed-graph')
    
    var width = "200"
    var height = "200"
    var radius = Math.min(width, height)/2
    
    var scheme = d3.schemeCategory10
    var color = d3.scaleOrdinal(scheme)
    
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

    drawLegend(container, openCloseData, scheme)
}

function drawSeverityChart(d3, block, crit, maj, min) {
    console.log(block, crit, maj, min)
    
    var severityData = [
        {label: 'blocked', count: block},
        {label: 'critical', count: crit},
        {label: 'major', count: maj},
        {label: 'minor', count: min}
    ]
    
    var container = document.getElementById('severity-graph')
    
    var width = '200'
    var height = '200'
    var radius = Math.min(width, height)/2
    
    var scheme = d3.schemeCategory10
    var color = d3.scaleOrdinal(scheme)
    
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
        
    drawLegend(container, severityData, scheme)
}

function drawLegend(container, data, colorScheme) {
    console.log(container)
    var swatchWidth = '24'
    var swatchHeight = '24'

    var legend = container.nextElementSibling
    console.log(legend)
    
    data.forEach((datum, i) => {
        const swatch = legend.appendChild(document.createElement('i'))
        const label = swatch.insertAdjacentElement('afterend', document.createElement('span'))

        swatch.style.backgroundColor = colorScheme[i]
        swatch.classList.add('legend-swatch')
        
        label.classList.add('legend-label')
        label.innerText = datum.label
    })
}

console.log('pie_charts.js loaded')