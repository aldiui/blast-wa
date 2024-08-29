import React from "react";
import { Head, Link, useForm } from "@inertiajs/react";
import {
  Button,
  Card,
  CardBody,
  CardFooter,
  CardHeader,
  FormControl,
  FormErrorMessage,
  FormLabel,
  Heading,
  Icon,
  Input,
  Text,
} from "@chakra-ui/react";
import { ArrowLeftIcon, BookmarkIcon } from "@heroicons/react/16/solid";
import AdminLayout from "../../../Layouts/AdminLayout";

const CreateKelas = ({ auth, sessions }) => {
  const { data, setData, post, processing, errors } = useForm({
    nama: "",
  });

  const submit = (e) => {
    e.preventDefault();
    post("/kelas");
  };

  return (
    <AdminLayout auth={auth} sessions={sessions}>
      <Head title="Tambah Kelas" />
      <Card maxW={"xl"} w="full" p={2} h={"auto"}>
        <CardHeader pb={0}>
          <Heading size="md" fontWeight="bold">
            Tambah Kelas
          </Heading>
        </CardHeader>
        <form onSubmit={submit}>
          <CardBody pb={0}>
            <FormControl mb={3} isInvalid={errors.nama}>
              <FormLabel htmlFor="nama" fontSize={"sm"}>
                Nama
                <Text display={"inline"} color="red">
                  *
                </Text>
              </FormLabel>
              <Input
                type="text"
                id="nama"
                value={data.nama}
                onChange={(e) => setData("nama", e.target.value)}
              />
              {errors.nama && (
                <FormErrorMessage fontSize={"xs"}>
                  {errors.nama}
                </FormErrorMessage>
              )}
            </FormControl>
          </CardBody>
          <CardFooter>
            <Button
              type="submit"
              colorScheme="green"
              isLoading={processing}
              loadingText="Simpan"
            >
              <Icon as={BookmarkIcon} mr={2} />
              Simpan
            </Button>
            <Button as={Link} href={"/kelas"} colorScheme="gray" ml={3}>
              <Icon as={ArrowLeftIcon} mr={2} />
              Kembali
            </Button>
          </CardFooter>
        </form>
      </Card>
    </AdminLayout>
  );
};

export default CreateKelas;
