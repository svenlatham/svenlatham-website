FROM docker.io/python:3-slim
RUN pip install Lektor
WORKDIR /src
COPY ./src/ /src
ENTRYPOINT ["lektor"]
