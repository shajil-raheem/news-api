## About News API
    An attempt to aggregate news headlines from various sources.
We've included integrations to The Guardian, The Newyork Times, and Open News for now.

## How To Set Up Using Terminal
### Open Terminal In Project Folder and Run The Commands 
<pre>
## please note that docker commands may require root/sudo permissions
docker compose up -d
docker exec -it news_api_laravel bash
## to be run inside the container bash
sh ./docker-php-setup.sh
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
exit
## back to host terminal
docker restart news_api_php_app
## done!
</pre>

## Postman Documentation Link

https://documenter.getpostman.com/view/42942410/2sAYk8u2wf

## API Documentation [Open API Format]
<pre>
{
  "openapi": "3.0.0",
  "info": {
    "title": "NewsAPI",
    "contact": {},
    "version": "1.0"
  },
  "servers": [
    {
      "url": "http://localhost:8000/api",
      "variables": {}
    }
  ],
  "paths": {
    "/user": {
      "post": {
        "tags": [
          "Misc"
        ],
        "summary": "Register",
        "operationId": "Register",
        "parameters": [
          {
            "name": "Accept",
            "in": "header",
            "description": "",
            "required": true,
            "style": "simple",
            "schema": {
              "type": "string",
              "example": "application/json"
            }
          }
        ],
        "requestBody": {
          "description": "",
          "content": {
            "application/json": {
              "schema": {
                "allOf": [
                  {
                    "$ref": "#/components/schemas/RegisterRequest"
                  },
                  {
                    "example": {
                      "name": "shajil",
                      "email": "shajilabdurahiman@gmail.com",
                      "password": "123456"
                    }
                  }
                ]
              },
              "example": {
                "name": "shajil",
                "email": "shajilabdurahiman@gmail.com",
                "password": "123456"
              }
            }
          },
          "required": true
        },
        "responses": {
          "200": {
            "description": "",
            "headers": {}
          }
        },
        "deprecated": false,
        "security": []
      },
      "get": {
        "tags": [
          "Misc"
        ],
        "summary": "Get User Details",
        "operationId": "GetUserDetails",
        "parameters": [],
        "responses": {
          "200": {
            "description": "",
            "headers": {}
          }
        },
        "deprecated": false
      }
    },
    "/get_reset_token": {
      "post": {
        "tags": [
          "Misc"
        ],
        "summary": "Get Password Reset Token",
        "operationId": "GetPasswordResetToken",
        "parameters": [
          {
            "name": "Accept",
            "in": "header",
            "description": "",
            "required": true,
            "style": "simple",
            "schema": {
              "type": "string",
              "example": "application/json"
            }
          }
        ],
        "requestBody": {
          "description": "",
          "content": {
            "application/json": {
              "schema": {
                "allOf": [
                  {
                    "$ref": "#/components/schemas/GetPasswordResetTokenRequest"
                  },
                  {
                    "example": {
                      "email": "shajilabdurahiman@gmail.com"
                    }
                  }
                ]
              },
              "example": {
                "email": "shajilabdurahiman@gmail.com"
              }
            }
          },
          "required": true
        },
        "responses": {
          "200": {
            "description": "",
            "headers": {}
          }
        },
        "deprecated": false,
        "security": []
      }
    },
    "/reset_password": {
      "post": {
        "tags": [
          "Misc"
        ],
        "summary": "Reset Password Using Token",
        "operationId": "ResetPasswordUsingToken",
        "parameters": [
          {
            "name": "Accept",
            "in": "header",
            "description": "",
            "required": true,
            "style": "simple",
            "schema": {
              "type": "string",
              "example": "application/json"
            }
          }
        ],
        "requestBody": {
          "description": "",
          "content": {
            "application/json": {
              "schema": {
                "allOf": [
                  {
                    "$ref": "#/components/schemas/ResetPasswordUsingTokenRequest"
                  },
                  {
                    "example": {
                      "token": "385908c3d020787dcad69829c0907ba7e0f8d202d291becbd50a57428c1799b8",
                      "email": "shajilabdurahiman@gmail.com",
                      "password": "123123",
                      "password_confirmation": "123123"
                    }
                  }
                ]
              },
              "example": {
                "token": "385908c3d020787dcad69829c0907ba7e0f8d202d291becbd50a57428c1799b8",
                "email": "shajilabdurahiman@gmail.com",
                "password": "123123",
                "password_confirmation": "123123"
              }
            }
          },
          "required": true
        },
        "responses": {
          "200": {
            "description": "",
            "headers": {}
          }
        },
        "deprecated": false,
        "security": []
      }
    },
    "/auth": {
      "post": {
        "tags": [
          "Misc"
        ],
        "summary": "Get Token",
        "operationId": "GetToken",
        "parameters": [
          {
            "name": "Accept",
            "in": "header",
            "description": "",
            "required": true,
            "style": "simple",
            "schema": {
              "type": "string",
              "example": "application/json"
            }
          }
        ],
        "requestBody": {
          "description": "",
          "content": {
            "application/json": {
              "schema": {
                "allOf": [
                  {
                    "$ref": "#/components/schemas/GetTokenRequest"
                  },
                  {
                    "example": {
                      "email": "shajilabdurahiman@gmail.com",
                      "password": "123123"
                    }
                  }
                ]
              },
              "example": {
                "email": "shajilabdurahiman@gmail.com",
                "password": "123123"
              }
            }
          },
          "required": true
        },
        "responses": {
          "200": {
            "description": "",
            "headers": {}
          }
        },
        "deprecated": false,
        "security": []
      }
    },
    "/news": {
      "get": {
        "tags": [
          "Misc"
        ],
        "summary": "News List API",
        "operationId": "NewsListAPI",
        "parameters": [
          {
            "name": "dateFrom",
            "in": "query",
            "description": "",
            "required": true,
            "style": "form",
            "explode": true,
            "schema": {
              "type": "string",
              "example": "2025-03-11"
            }
          },
          {
            "name": "dateTo",
            "in": "query",
            "description": "",
            "required": true,
            "style": "form",
            "explode": true,
            "schema": {
              "type": "string"
            }
          },
          {
            "name": "category",
            "in": "query",
            "description": "",
            "required": true,
            "style": "form",
            "explode": true,
            "schema": {
              "type": "string"
            }
          },
          {
            "name": "keyword",
            "in": "query",
            "description": "",
            "required": true,
            "style": "form",
            "explode": true,
            "schema": {
              "type": "string"
            }
          },
          {
            "name": "offset",
            "in": "query",
            "description": "",
            "required": true,
            "style": "form",
            "explode": true,
            "schema": {
              "type": "string"
            }
          },
          {
            "name": "limit",
            "in": "query",
            "description": "",
            "required": true,
            "style": "form",
            "explode": true,
            "schema": {
              "type": "string"
            }
          },
          {
            "name": "Accept",
            "in": "header",
            "description": "",
            "required": true,
            "style": "simple",
            "schema": {
              "type": "string",
              "example": "application/json"
            }
          }
        ],
        "responses": {
          "200": {
            "description": "",
            "headers": {}
          }
        },
        "deprecated": false
      }
    },
    "/preferences": {
      "get": {
        "tags": [
          "Misc"
        ],
        "summary": "Get Preferences",
        "operationId": "GetPreferences",
        "parameters": [
          {
            "name": "Accept",
            "in": "header",
            "description": "",
            "required": true,
            "style": "simple",
            "schema": {
              "type": "string",
              "example": "application/json"
            }
          }
        ],
        "responses": {
          "200": {
            "description": "",
            "headers": {}
          }
        },
        "deprecated": false
      },
      "put": {
        "tags": [
          "Misc"
        ],
        "summary": "Set Preferences",
        "operationId": "SetPreferences",
        "parameters": [
          {
            "name": "Accept",
            "in": "header",
            "description": "",
            "required": true,
            "style": "simple",
            "schema": {
              "type": "string",
              "example": "application/json"
            }
          }
        ],
        "requestBody": {
          "description": "",
          "content": {
            "application/json": {
              "schema": {
                "allOf": [
                  {
                    "$ref": "#/components/schemas/SetPreferencesRequest"
                  },
                  {
                    "example": {
                      "authors": [
                        "Alexis Soloski",
                        "Andrew E. Kramer and Alan Rappeport",
                        "Andrew Sparrow",
                        "Anna Kodé",
                        "Annie Aguiar",
                        "Anson Frericks",
                        "Anton Troianovski",
                        "Catie Edmondson",
                        "Christina Goldbaum and Reham Mourshed",
                        "Claire Cain Miller",
                        "Claire Fahy",
                        "Dan Milmo",
                        "Danny Hakim and David W. Chen"
                      ],
                      "categories": [
                        "news"
                      ],
                      "sources": [
                        "the_guardian",
                        "ny_times"
                      ]
                    }
                  }
                ]
              },
              "example": {
                "authors": [
                  "Alexis Soloski",
                  "Andrew E. Kramer and Alan Rappeport",
                  "Andrew Sparrow",
                  "Anna Kodé",
                  "Annie Aguiar",
                  "Anson Frericks",
                  "Anton Troianovski",
                  "Catie Edmondson",
                  "Christina Goldbaum and Reham Mourshed",
                  "Claire Cain Miller",
                  "Claire Fahy",
                  "Dan Milmo",
                  "Danny Hakim and David W. Chen"
                ],
                "categories": [
                  "news"
                ],
                "sources": [
                  "the_guardian",
                  "ny_times"
                ]
              }
            }
          },
          "required": true
        },
        "responses": {
          "200": {
            "description": "",
            "headers": {}
          }
        },
        "deprecated": false
      }
    },
    "/personalized": {
      "get": {
        "tags": [
          "Misc"
        ],
        "summary": "Personalized News",
        "operationId": "PersonalizedNews",
        "parameters": [
          {
            "name": "limit",
            "in": "query",
            "description": "",
            "required": true,
            "style": "form",
            "explode": true,
            "schema": {
              "type": "integer",
              "format": "int32",
              "example": 50
            }
          },
          {
            "name": "offset",
            "in": "query",
            "description": "",
            "required": true,
            "style": "form",
            "explode": true,
            "schema": {
              "type": "integer",
              "format": "int32",
              "example": 0
            }
          },
          {
            "name": "Accept",
            "in": "header",
            "description": "",
            "required": true,
            "style": "simple",
            "schema": {
              "type": "string",
              "example": "application/json"
            }
          }
        ],
        "responses": {
          "200": {
            "description": "",
            "headers": {}
          }
        },
        "deprecated": false
      }
    }
  },
  "components": {
    "schemas": {
      "RegisterRequest": {
        "title": "RegisterRequest",
        "required": [
          "name",
          "email",
          "password"
        ],
        "type": "object",
        "properties": {
          "name": {
            "type": "string"
          },
          "email": {
            "type": "string"
          },
          "password": {
            "type": "string"
          }
        },
        "example": {
          "name": "shajil",
          "email": "shajilabdurahiman@gmail.com",
          "password": "123456"
        }
      },
      "GetPasswordResetTokenRequest": {
        "title": "GetPasswordResetTokenRequest",
        "required": [
          "email"
        ],
        "type": "object",
        "properties": {
          "email": {
            "type": "string"
          }
        },
        "example": {
          "email": "shajilabdurahiman@gmail.com"
        }
      },
      "ResetPasswordUsingTokenRequest": {
        "title": "ResetPasswordUsingTokenRequest",
        "required": [
          "token",
          "email",
          "password",
          "password_confirmation"
        ],
        "type": "object",
        "properties": {
          "token": {
            "type": "string"
          },
          "email": {
            "type": "string"
          },
          "password": {
            "type": "string"
          },
          "password_confirmation": {
            "type": "string"
          }
        },
        "example": {
          "token": "385908c3d020787dcad69829c0907ba7e0f8d202d291becbd50a57428c1799b8",
          "email": "shajilabdurahiman@gmail.com",
          "password": "123123",
          "password_confirmation": "123123"
        }
      },
      "GetTokenRequest": {
        "title": "GetTokenRequest",
        "required": [
          "email",
          "password"
        ],
        "type": "object",
        "properties": {
          "email": {
            "type": "string"
          },
          "password": {
            "type": "string"
          }
        },
        "example": {
          "email": "shajilabdurahiman@gmail.com",
          "password": "123123"
        }
      },
      "SetPreferencesRequest": {
        "title": "SetPreferencesRequest",
        "required": [
          "authors",
          "categories",
          "sources"
        ],
        "type": "object",
        "properties": {
          "authors": {
            "type": "array",
            "items": {
              "type": "string"
            },
            "description": ""
          },
          "categories": {
            "type": "array",
            "items": {
              "type": "string"
            },
            "description": ""
          },
          "sources": {
            "type": "array",
            "items": {
              "type": "string"
            },
            "description": ""
          }
        },
        "example": {
          "authors": [
            "Alexis Soloski",
            "Andrew E. Kramer and Alan Rappeport",
            "Andrew Sparrow",
            "Anna Kodé",
            "Annie Aguiar",
            "Anson Frericks",
            "Anton Troianovski",
            "Catie Edmondson",
            "Christina Goldbaum and Reham Mourshed",
            "Claire Cain Miller",
            "Claire Fahy",
            "Dan Milmo",
            "Danny Hakim and David W. Chen"
          ],
          "categories": [
            "news"
          ],
          "sources": [
            "the_guardian",
            "ny_times"
          ]
        }
      }
    },
    "securitySchemes": {
      "httpBearer": {
        "type": "http",
        "scheme": "bearer"
      }
    }
  },
  "security": [
    {
      "httpBearer": []
    }
  ],
  "tags": [
    {
      "name": "Misc",
      "description": ""
    }
  ]
}
</pre>