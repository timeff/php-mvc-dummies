
class App extends React.Component{
    constructor(props){
        super(props)
        this.state={
            answer:{}
        }
        this.getAnswer = this.getAnswer.bind(this)
    }

    getAnswer(amt,pb,int,freq){
        fetch(`api.php?amt=${amt}&pb=${pb}&int=${int}&freq=${freq}`)
        .then(jsonResponse=>jsonResponse.json())
        .then(data=> this.setState({answer:data}))
        .then(()=>{
            this.drawGraph(this.state.answer.graph)
        })
    }

    drawGraph(data){
        d3.select("svg").remove();
        var svg1 = dimple.newSvg("#chart", 800, 400)
        var chart1 = new dimple.chart(svg1)
        var xAxis = chart1.addCategoryAxis("x", "Month")
        var yAxis = chart1.addLogAxis("y", "THB")

        for (var i in data){
            var temp=chart1.addSeries(i,dimple.plot.line)
            temp.data=data[i]
            temp.plot=dimple.plot.line
        }
        chart1.addLegend(250, 10, 500, 20, "right");
        chart1.draw(1000)
    }

    render(){
        return (<div className='container'>
                    <InputPanel exe={this.getAnswer}/>
                    <AnswerPanel ans={this.state.answer}/>
                </div>)
    }
}

class InputPanel extends React.Component{
    constructor(props){
        super(props)
        this.state={
            amount:'',
            payback:'',
            interest:0.035,
            freq:12,
            showInterest:false,
            showFreq:false
        }
    }

    handleSubmit(e){
        e.preventDefault()
        this.props.exe(this.state.amount,this.state.payback,this.state.interest,this.state.freq)
    }
    handleAmount(e){
        this.setState({amount:e.target.value})
    }
    handlePayback(e){
        this.setState({payback:e.target.value})
    }
    handleInterest(e){
        this.setState({interest:e.target.value})
    }
    handleFreq(e){
        this.setState({freq:e.target.value})
    }
    handleShowInterest(){
        this.setState({showInterest:!this.state.showInterest})
    }
    handleShowFreq(){
        this.setState({showFreq:!this.state.showFreq})
    }

    render(){
        return (<div className='panel panel-default'>
                    <div className='panel-heading'>
                        <h2 className='panel-title'>Loan Calculator</h2>
                    </div>
                    <div className='panel-body'>
                        <form className='form' onSubmit={this.handleSubmit.bind(this)}>
                            <div className='form-group'>
                                <label htmlFor='amount'>Total Amount </label>
                                <input type='number' className='form-control' id='amount' placeholder='eg. 10000' value={this.state.amount} onChange={this.handleAmount.bind(this)}/>
                            </div>
                            <div className='form-group'>
                                <label htmlFor='payback'>Payback Period (Years) </label>
                                <input type='number' className='form-control' id='payback' placeholder='eg. 5' value={this.state.payback} onChange={this.handlePayback.bind(this)}/>
                            </div>

                            { this.state.showInterest ?
                            <div className='form-group'>
                                <label htmlFor='interest'>Interest per year </label>
                                <input type='number' className='form-control' id='interest' placeholder='eg. 0.035' value={this.state.interest} onChange={this.handleInterest.bind(this)}/>
                            </div> : null
                            }

                            { this.state.showFreq ?
                            <div className='form-group'>
                                <label htmlFor='freq'>Frequency per year </label>
                                <input type='number' className='form-control' id='freq' placeholder='eg. 12' value={this.state.freq} onChange={this.handleFreq.bind(this)}/>
                            </div> : null
                            }

                            <div className='form-group'>
                                <button type='submit' className='btn btn-primary'>Calculate!</button>
                                
                                &nbsp;
                                <input type='checkbox' 
                                checked={this.state.showInterest} 
                                onChange={this.handleShowInterest.bind(this)}
                                /> 
                                Interest Rate Adjust 

                                &nbsp;
                                <input type='checkbox' 
                                checked={this.state.showFreq} 
                                onChange={this.handleShowFreq.bind(this)}/> 
                             
                                Frequency Adjust
                            </div>
                        </form>
                    </div>
                </div>)
    }
}

class AnswerPanel extends React.Component{
    constructor(props){
        super(props)
    }
    render(){
        var textAns='Please fill the form'
        if (this.props.ans.amountPerMonth){
            textAns = 'You have to pay '+parseInt(this.props.ans.amountPerMonth)+' THB per month'
        }

        return (
            <div className='jumbotron'>
                <h2 className='text-center'>{textAns}</h2>
                <div className='text-center' id='chart'></div>
            </div>
        )
    }
}


ReactDOM.render(
  <App />,
  document.getElementById('app')
);
