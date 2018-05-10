/* eventually this module should work by:
    - taking args (elementToSelect, dataToRender)
    - dataToRender will be passed from php MySQL query
    - this will require refactoring fileend.php into a fcn
*/
function drawPieChart(container, jsonData, colorData, d3 = window.d3) {
    console.log(jsonData, colorData);
    const w = '200';
    const h = '200';
    const r = Math.min(w, h)/2;
    
    const color = typeof colorData !== 'function' ?
        d3.scaleOrdinal()
            .domain(
                jsonData
                .reduce((acc, el, i) => {
                    acc.push(el.label);
                    if (acc.length === jsonData.length) console.log(acc);
                    return acc;
                }, []))
            .range(Object.values(colorData))
        : d3.scaleSequential().domain([0, jsonData.length - 1]).interpolator(colorData);
        
    console.log(color.domain(), color.range ? color.range() : color.interpolator());
    
    const svg = d3.select(container)
        .append('svg')
        .attr('width', w)
        .attr('height', h)
        .append('g')
        .attr('transform', `translate(${w/2},${h/2})`)
        
    const arc = d3.arc()
        .innerRadius(0)
        .outerRadius(r)
        
    const pie = d3.pie()
        .value(d => d.value)
        .sort(null)
        
    const arcs = svg.selectAll('g.slice')
        .data(pie(jsonData))
        .enter()
        .append('g')
        .attr('class', 'slice');
        
    arcs.append('path')
        .attr('d', arc)
        .attr('fill', d => {
            // if color.range, then color does not use an interpolator
            if (color.range) return color(d.data.label);
            else return color(d.index);
        })
        
    arcs.append('text')
        .attr('transform', d => {
            d.innerRadius = 0;
            d.outerRadius = r;
            return `translate(${arc.centroid(d)})`;
        })
        .attr('text-anchor', 'middle')
        .text((d, i) => i + 1);
        
    drawLegend(container, jsonData, Object.values(colorData));
}

function drawLegend(container, data, colorScheme) {
    var legend = container.nextElementSibling

    data.forEach((datum, i) => {
        const label = legend.appendChild(document.createElement('span'))
        const swatch = document.createElement('i')
        
        label.classList.add('legend-label')
        label.textContent = datum.label

        swatch.innerText = i + 1;
        swatch.classList.add('legend-swatch')
        swatch.style.backgroundColor = colorScheme[i]
        label.insertAdjacentElement('afterbegin', swatch)
    })
}

function returnDataLabel(fn, data) {
    return fn(data.label);
}