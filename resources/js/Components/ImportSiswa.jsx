import React from "react";
import {
  Button,
  Modal,
  ModalOverlay,
  ModalContent,
  ModalHeader,
  ModalFooter,
  ModalBody,
  FormControl,
  FormLabel,
  Input,
  ModalCloseButton,
  useDisclosure,
  Icon,
  FormErrorMessage,
} from "@chakra-ui/react";
import {
  ArrowDownIcon,
  BookmarkIcon,
  XMarkIcon,
} from "@heroicons/react/16/solid";
import { useForm } from "@inertiajs/react";

const ImportSiswa = () => {
  const { data, setData, post, processing, errors, reset } = useForm({
    file: null,
  });

  const { isOpen, onOpen, onClose } = useDisclosure();

  const handleFileChange = (e) => {
    setData("file", e.target.files[0]);
  };

  const handleClose = () => {
    reset();
    onClose();
  };

  const submit = (e) => {
    e.preventDefault();

    post("/siswa/import", data, {
      onFinish: handleClose,
    });
  };

  return (
    <>
      <Button onClick={onOpen} colorScheme="orange" variant="solid" size="sm">
        <Icon as={ArrowDownIcon} mr={2} />
        Import Siswa
      </Button>

      <Modal isOpen={isOpen} onClose={handleClose} size="lg">
        <form onSubmit={submit}>
          <ModalOverlay />
          <ModalContent m={6}>
            <ModalHeader>Import Excel</ModalHeader>
            <ModalCloseButton />
            <ModalBody>
              <FormControl isInvalid={errors.file}>
                <FormLabel>File Excel</FormLabel>
                <Input type="file" onChange={handleFileChange} />
                {errors.file && (
                  <FormErrorMessage>{errors.file}</FormErrorMessage>
                )}
              </FormControl>
            </ModalBody>

            <ModalFooter>
              <Button
                type="submit"
                colorScheme="green"
                isLoading={processing}
                loadingText="Simpan"
                mr={3}
              >
                <Icon as={BookmarkIcon} mr={2} />
                Simpan
              </Button>
              <Button size="sm" colorScheme="gray" onClick={handleClose}>
                <Icon as={XMarkIcon} mr={2} />
                Batal
              </Button>
            </ModalFooter>
          </ModalContent>
        </form>
      </Modal>
    </>
  );
};

export default ImportSiswa;
