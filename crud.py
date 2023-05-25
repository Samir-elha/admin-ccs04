from fastapi.security import OAuth2PasswordBearer
from sqlalchemy.orm import Session
import secrets

from sendgrid import SendGridAPIClient
from sendgrid.helpers.mail import Mail

import auth
import models
import schemas

oauth2_scheme = OAuth2PasswordBearer(tokenUrl="token")

def get_user(db: Session, user_id: int):
    return db.query(models.User).filter(models.User.id == user_id).first()

def get_user_by_email(db: Session, email: str):
    return db.query(models.User).filter(models.User.email == email).first()

def get_users(db: Session, skip: int = 0, limit: int = 100):
    return db.query(models.User).offset(skip).limit(limit).all()

def create_user(db: Session, user: schemas.UserCreate):
    hashed_password = auth.get_password_hash(user.password)
    db_user = models.User(email=user.email, hashed_password=hashed_password)
    # Generate a random token
    token = secrets.token_hex(16)  # generates a random token
    db_user.token = token
    db.add(db_user)
    db.commit()
    db.refresh(db_user)
    return db_user

def get_items(db: Session, skip: int = 0, limit: int = 100):
    return db.query(models.Item).offset(skip).limit(limit).all()

def create_user_item(db: Session, item: schemas.ItemCreate, user_id: int):
    db_item = models.Item(**item.dict(), owner_id=user_id)
    db.add(db_item)
    db.commit()
    db.refresh(db_item)
    return db_item

def update_user(db: Session, user: schemas.UserUpdate, db_user: models.User) -> models.User:
    db_user.email = user.email or db_user.email
    db_user.full_name = user.full_name or db_user.full_name
    db_user.disabled = user.disabled or db_user.disabled
    db.add(db_user)
    db.commit()
    db.refresh(db_user)
    return db_user

def delete_user(db: Session, user_id: int):
    db_user = db.query(models.User).filter(models.User.id == user_id).first()
    db.delete(db_user)
    db.commit()
    return db_user

def send_email(to_email: str, subject: str, content: str):
    message = Mail(
        from_email=SENDGRID_FROM_EMAIL,
        to_emails=to_email,
        subject=subject,
        html_content=content
    )
    sg = SendGridAPIClient(api_key=SENDGRID_API_KEY)
    response = sg.send(message)
    return response

def get_user_by_email(db: Session, email: str) -> [models.User]:
    return db.query(models.User).filter(models.User.email == email).first()

def get_user_dir(db: Session, email: str) -> str:
    user = get_user_by_email(db, email)
    return f'/home/{user.email}/files'  # update this to reflect your directory structure

# def upload_file_to_remote(local_path, filename, hostname, port, username, email):
#     # Get the user's directory path
#     db = SessionLocal()
#     remote_path = crud.get_user_dir(db, email)
#     db.close()

#     # Prompt user for SSH password
#     password = getpass.getpass("Enter SSH password: ")

#     # Create an SSH client and connect to the remote server
#     ssh = paramiko.SSHClient()
#     ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
#     ssh.connect(hostname=hostname, port=port, username=username, password=password)

#     # Create an SFTP client and upload the file
#     sftp = ssh.open_sftp()
#     sftp.put(local_path, f"{remote_path}/{filename}")
#     sftp.close()

#     # Close the SSH client
#     ssh.close()


