import React, {useState, useEffect} from 'react'

const InputEmail = ({pasteAction, value, emailInjection, index, validation, clearValidation}) => {

    // const [inputValue, setInputValue] = useState('');
    const [errorState, setErrorState] = useState(false);
    useEffect(() => {
        // if error state is currently active and inputValue change, error state drop
        if (errorState){
            clearValidation();
            setErrorState(false);
        } else {
            if (!validation){
                setErrorState(true);
            }
        }
    }, [value, validation])

    return (
        <div style={{display: 'flex', flexDirection:'column'}}>
            <input value={value} onChange={(e) => {emailInjection(e.target.value)}} placeholder='Enter your email address' onPaste={(e) => { pasteAction(e, index); e.preventDefault(); return false}}/>
            {errorState && <span style={{color: "red"}}>Email is not valid! Please check your input!</span>}
        </div>
    )
}

export default InputEmail