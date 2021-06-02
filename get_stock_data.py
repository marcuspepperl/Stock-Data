import yfinance as yf
from flask import Flask, request, Response

# Create the flask application
app = Flask(__name__)


@app.route('/')
def index():
    response = Response()
    response.headers["Access-Control-Allow-Origin"] = "*"
    data = ""
    try:
        stockTicker = request.args.get("stockTicker")
        startDate = request.args.get("startDate")
        endDate = request.args.get("endDate")

        companyData = yf.Ticker(stockTicker).history(start=startDate, end=endDate)
        highPrice = str(round(companyData['High'].max(), 2))
        lowPrice = str(round(companyData['Low'].min(), 2))

        data = "&".join([stockTicker, lowPrice, highPrice])
    except Exception:
        data = "error"
    response.data = data
    return response
