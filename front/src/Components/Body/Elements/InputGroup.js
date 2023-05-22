import React, { useEffect, useState } from 'react'
import InputEmail from './InputEmail';

// Validate email address
// return true if email is validated
export const ValidateEmail = (email) => {
    return String(email)
        .toLowerCase()
        .match(
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        )
}

const InputGroup = () => {

    const [emails, setEmails] = useState([]);
    const [validations, setValidations] = useState([]);

    function clearValidation(index) {
        let temp_array = validations;
        if (index < temp_array.length){
            temp_array[index] = true;
            setValidations(temp_array);
        }
    }

    // Custom function for button click
    function onButtonClick () {
        // check validation of each email
        setValidations(emails.map(email => email=="" || ValidateEmail(email)));
        // if some of emails are not validated - stop sending process
        if (validations.includes(true)) return;
        emails.map( email =>{
            // this will prevent sending on "empty" email
            if (!ValidateEmail(email)){
                // setErrorState(true);
                return;
            }
            fetch("/backend/api/email/create.php", {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    email: email
                }),
            }).then((response) => {
                response.json(data => {
                    if (data.error){
                        console.error('Create data error:', data.error);
                    }
                })
            }).catch(error => {
                console.error('Request error', error);
            }) 
        });
    }

    // Pasting bunch of emails from excel on particular index
    function pasting(e, index = 0) {
        // create copy of emails array to manipulate
        let CEmails = emails
        // getting array from clipboard
        const temp_emails = e.clipboardData.getData('Text').split('\r\n')


        for (let i = index; i < temp_emails.length; i++) {
        CEmails[i] = temp_emails[i - index]
        }
        console.log('after paste', CEmails)
        // spread used to renew pointer, so hooks work correctly
        setEmails([...CEmails])
        // setPasteIndex(index);
    }

    // Getting email inputs
    const getEmailInputs = (emails) => {

        const getValue = (i) => {
            return (
                <InputEmail
                    pasteAction={pasting}
                    value={emails[i]}
                    emailInjection={(email) => {
                        let CEmails = emails;
                        CEmails[i] = email;
                        setEmails([...CEmails]);
                    }}
                    validation={i >= validations.length ? true : validations[i]}
                    clearValidation={() => clearValidation(i)}
                    index={i}
                    key={i}
                    // decreaseSendings={decreaseSendings}
                />
            )
        }
        return [...Array(emails.length + 1)].map((e, i) => getValue(i))
    }

    return (
    <div className='InputGroup'>
        <div style={{width: '100%', height: '339px', position: 'relative'}}>
            <div
                style={{
                    width: '100%',
                    height: '100%',
                    position: 'absolute',
                    overflowY: 'scroll',
                }}
                className="emailHolder_absolute"
            >
                {getEmailInputs(emails)}
            </div>
        </div>
        <button onClick={onButtonClick} disabled={emails.filter(email => email!="").length == 0}>Send me some wisdom</button>
    </div>
    )
}

export default InputGroup