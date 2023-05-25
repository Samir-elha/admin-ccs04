from fastapi import Depends, FastAPI, HTTPException, Depends, status
from fastapi import UploadFile, File
from fastapi import Body
from fastapi.security import OAuth2PasswordBearer, OAuth2PasswordRequestForm
from sqlalchemy.orm import Session
from typing import List
from pathlib import Path
from jose import JWTError, jwt

from sendgrid import SendGridAPIClient
from sendgrid.helpers.mail import Mail

import secrets
import paramiko
import os
from auth import get_current_user
import auth
import crud
import models
import schemas
from database import SessionLocal, engine
from auth import get_password_hash


print("We are in the main.......")
if not os.path.exists('.\sqlitedb'):
    print("Making folder.......")
    os.makedirs('.\sqlitedb')

print("Creating tables.......")
models.Base.metadata.create_all(bind=engine)
print("Tables created.......")

app = FastAPI()

Email = "r0897980@student.thomasmore.be"

# oauth2_scheme = OAuth2PasswordBearer(tokenUrl="token")

user_files = {}

# SendGrid configuratie
SENDGRID_API_KEY = "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"
SENDGRID_FROM_EMAIL = Email

# Instantieer SendGrid-client
sg = SendGridAPIClient(api_key=SENDGRID_API_KEY)


# Dependency
def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()

def send_email(to_email: str, subject: str, content: str):
    message = Mail(from_email=SENDGRID_FROM_EMAIL, to_emails=to_email, subject=subject, html_content=content)
    try:
        response = sg.send(message)
        print(response.status_code)
        print(response.body)
        print(response.headers)
    except Exception as e:
        print(e)


oauth2_scheme = OAuth2PasswordBearer(tokenUrl="token")

#Authenticatie
@app.post("/token")
def login_for_access_token(form_data: OAuth2PasswordRequestForm = Depends(), db: Session = Depends(get_db)):
    user = auth.authenticate_user(db, form_data.username, form_data.password)
    if not user:
        raise HTTPException(
            status_code=401,
            detail="Incorrect username or password",
            headers={"WWW-Authenticate": "Bearer"},
        )
    access_token = auth.create_access_token(
        data={"sub": user.email}
    )
    return {"access_token": access_token, "token_type": "bearer"}

#mailsserver
@app.post("/send-email/")
def send_email_handler(to_email: str, subject: str, content: str, token: str = Depends(oauth2_scheme)):
    message = Mail(from_email=SENDGRID_FROM_EMAIL, to_emails=to_email, subject=subject, html_content=content)
    try:
        response = sg.send(message)
        print(response.status_code)
        print(response.body)
        print(response.headers)
    except Exception as e:
        print(e)
    return {"message": "Email sent"}



#USERS
@app.post("/users/", response_model=schemas.User)
def create_user(user: schemas.UserCreate, db: Session = Depends(get_db)):
    hashed_password = get_password_hash(user.password)
    db_user = models.User(email=user.email, hashed_password=hashed_password)
    # if db_user:
    #     raise HTTPException(status_code=400, detail="Email already registered")
    db_user = crud.create_user(db=db, user=user)
    
    token = secrets.token_hex(16)  # generates a random token
    db_user.token = token
    db.add(db_user)
    db.commit()
    db.refresh(db_user)

    send_welcome_email(user.email)  # this line sends the email after a successful registration
    return db_user

def send_welcome_email(email: str):
    message = Mail(
        from_email=SENDGRID_FROM_EMAIL,
        to_emails=email,
        subject='Welcome to Our Platform',
        plain_text_content='Thank you for registering with us.',
        html_content='<strong>Thank you for registering with us.</strong>')
    try:
        sendgrid_client = SendGridAPIClient(SENDGRID_API_KEY)
        response = sendgrid_client.send(message)
        print(response.status_code)
        print(response.body)
        print(response.headers)
    except Exception as e:
        print(e.message)



@app.get("/users/", response_model=list[schemas.User])
def read_users(skip: int = 0, limit: int = 100, db: Session = Depends(get_db), token: str = Depends(oauth2_scheme)):
    users = crud.get_users(db, skip=skip, limit=limit)
    return users


@app.get("/users/me", response_model=schemas.User)
def read_users_me(db: Session = Depends(get_db), token: str = Depends(oauth2_scheme)):
    current_user = auth.get_current_active_user(db, token)
    return current_user


@app.get("/users/{user_id}", response_model=schemas.User)
def read_user(user_id: int, db: Session = Depends(get_db), token: str = Depends(oauth2_scheme)):
    db_user = crud.get_user(db, user_id=user_id)
    if db_user is None:
        raise HTTPException(status_code=404, detail="User not found")
    return db_user


@app.post("/users/{user_id}/items/", response_model=schemas.Item)
def create_item_for_user(
    user_id: int, item: schemas.ItemCreate, db: Session = Depends(get_db), token: str = Depends(oauth2_scheme)):
    return crud.create_user_item(db=db, item=item, user_id=user_id)


@app.get("/items/", response_model=list[schemas.Item])
def read_items(skip: int = 0, limit: int = 100, db: Session = Depends(get_db), token: str = Depends(oauth2_scheme)):
    items = crud.get_items(db, skip=skip, limit=limit)
    return items

@app.put("/users/{user_id}", response_model=schemas.User)
def update_user(user_id: int, user: schemas.UserUpdate, db: Session = Depends(get_db), token: str = Depends(oauth2_scheme)):
    db_user = crud.get_user(db, user_id=user_id)
    if db_user is None:
        raise HTTPException(status_code=404, detail="User not found")
    updated_user = crud.update_user(db=db, user=user, db_user=db_user)
    return updated_user


@app.delete("/users/{user_id}/", response_model=schemas.User)
def delete_user(user_id: int, db: Session = Depends(get_db), token: str = Depends(oauth2_scheme)):
    db_user = crud.get_user(db, user_id=user_id)
    if db_user is None:
        raise HTTPException(status_code=404, detail="User not found")
    crud.delete_user(db=db, user_id=user_id)
    return db_user

#upload
@app.post("/upload")
async def upload_file(file: UploadFile = File(...)):
    # SFTP connection details
    hostname = "172.26.104.0"
    port = 22
    username = "admin-ccs04"
    password = "admin-ccs04"

    # Destination directory on the virtual machine
    destination_directory = "/home/admin-ccs04/API/files/"

    try:
        # Create an SSH client and connect to the virtual machine
        ssh_client = paramiko.SSHClient()
        ssh_client.load_system_host_keys()
        ssh_client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        ssh_client.connect(hostname, port, username, password)

        # Open an SFTP session
        sftp = ssh_client.open_sftp()

        # Upload the file to the destination directory
        destination_path = destination_directory + file.filename
        sftp.putfo(file.file, destination_path)

        # Close the SFTP session and the SSH connection
        sftp.close()
        ssh_client.close()

        return {"message": "File uploaded successfully."}

    except Exception as e:
        return {"message": str(e)}


# Update a file
@app.put("/updatefile/{file_id}")
async def update_file(file_id: int, file: UploadFile = File(...), token: str = Depends(oauth2_scheme)):
    return {"file_id": file_id, "filename": file.filename}

# Delete a file
@app.delete("/deletefile/{file_id}")
async def delete_file(file_id: int, token: str = Depends(oauth2_scheme)):
    return {"file_id": file_id, "status": "deleted"}

# User toekennen aan files
@app.post("/projects/{project_id}/users/", response_model=schemas.User)
def add_user_to_project(project_id: int, user: schemas.UserCreate, db: Session = Depends(get_db), token: str = Depends(oauth2_scheme)):
    db_project = crud.get_project(db, project_id=project_id)
    if not db_project:
        raise HTTPException(status_code=404, detail="Project not found")
    db_user = crud.get_user_by_email(db, email=user.email)
    if not db_user:
        raise HTTPException(status_code=404, detail="User not found")
    crud.add_user_to_project(db=db, project=db_project, user=db_user)
    return db_user
